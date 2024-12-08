const { Client } = require("whatsapp-web.js");
const QRCode = require("qrcode");
const mysql = require("mysql2/promise");
const express = require("express");
const cors = require("cors");
const app = express();
const server = require("http").createServer(app);
const io = require("socket.io")(server, {
    cors: {
        origin: "http://127.0.0.1:8000",
        methods: ["GET", "POST"],
        credentials: true,
    },
});

app.use(
    cors({
        origin: "http://127.0.0.1:8000",
        credentials: true,
    })
);

app.use((req, res, next) => {
    res.header("Access-Control-Allow-Origin", "http://127.0.0.1:8000");
    res.header("Access-Control-Allow-Credentials", "true");
    res.header(
        "Access-Control-Allow-Headers",
        "Origin, X-Requested-With, Content-Type, Accept"
    );
    next();
});

const client = new Client({
    puppeteer: {
        headless: true,
        args: ["--no-sandbox"],
    },
});

const dbConfig = {
    host: "localhost",
    user: "root",
    password: "",
    database: "yupiii",
};

let firstMessages = new Set();
let menuSent = new Set();
let isAuthenticated = false;
let isClientInitialized = false;
let currentQR = null;

// Modificamos la URL de redirección
const REDIRECT_URL = "/admin/whatsapp/columns-atention";

async function saveMessage(phoneNumber, message, isFromMe, messageId, agentStatus) {
    const connection = await mysql.createConnection(dbConfig);
    try {
        await connection.execute(
            "INSERT INTO whatsapp_messages (phone_number, message, is_from_me, message_id, agent_status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())",
            [phoneNumber, message, isFromMe ? 1 : 0, messageId, agentStatus]
        );
    } catch (error) {
        console.error("Error guardando mensaje:", error);
    } finally {
        await connection.end();
    }
}

async function getMessageHistory(phoneNumber) {
    const connection = await mysql.createConnection(dbConfig);
    try {
        const [messages] = await connection.execute(
            "SELECT * FROM whatsapp_messages WHERE phone_number = ? ORDER BY created_at ASC",
            [phoneNumber]
        );
        return messages;
    } catch (error) {
        console.error("Error obteniendo mensajes:", error);
        return [];
    } finally {
        await connection.end();
    }
}

function handleAuthentication(socket) {
    if (isAuthenticated) {
        socket.emit("redirectToMessages", {
            authenticated: true,
            redirectUrl: REDIRECT_URL,
        });
    }
}

client.on("ready", () => {
    console.log("WhatsApp client is ready");
    isAuthenticated = true;
    currentQR = null;
    io.emit("redirectToMessages", {
        authenticated: true,
        redirectUrl: REDIRECT_URL,
    });
});

client.on("qr", async (qr) => {
    try {
        currentQR = await QRCode.toDataURL(qr);
        console.log("QR emitido:", currentQR.substring(0, 50));
        io.emit("qrCode", currentQR);
    } catch (error) {
        console.error("QR error:", error);
    }
});

client.on("authenticated", () => {
    isAuthenticated = true;
    currentQR = null;
    io.emit("redirectToMessages", {
        authenticated: true,
        redirectUrl: REDIRECT_URL,
    });
    console.log("WhatsApp authenticated");
});

client.on("message", async (message) => {
    try {
        const content = message.body.toLowerCase();
        const sender = message.from;

        await saveMessage(sender, message.body, false, message.id._serialized, null);
        io.emit("messageReceived", {
            from: sender,
            message: message.body,
            id: message.id._serialized,
            timestamp: new Date(),
        });

        if (!firstMessages.has(sender)) {
            const welcomeMsg = `👋 ¡Bienvenido a Yupiii.cl!

🏪 Tu tienda online de confianza

Estamos aquí para ayudarte con lo que necesites.`;

            firstMessages.add(sender);
            await message.reply(welcomeMsg);
            await saveMessage(sender, welcomeMsg, true, message.id._serialized + "_welcome", null);
            io.emit("messageSent", {
                to: sender,
                message: welcomeMsg,
                id: message.id._serialized + "_welcome",
                timestamp: new Date(),
            });

            setTimeout(async () => {
                const menuMsg = `Por favor seleccione una opción:

🛍️ 1. Producto

📦 2. Seguimiento pedido

🧾 3. Factura o boleta

👩‍💼 4. Atención de agente

Responda con el número de la opción deseada`;

                await message.reply(menuMsg);
                await saveMessage(sender, menuMsg, true, message.id._serialized + "_menu", null);
                menuSent.add(sender);
                io.emit("messageSent", {
                    to: sender,
                    message: menuMsg,
                    id: message.id._serialized + "_menu",
                    timestamp: new Date(),
                });
            }, 1000);
            return;
        }

        if (!menuSent.has(sender)) {
            return;
        }

        const connection = await mysql.createConnection(dbConfig);

        try {
            if (content === "menu" || content === "0") {
                const menuMsg = `¡Bienvenido! 👋 Por favor seleccione una opción:

🛍️ 1. Producto

📦 2. Seguimiento pedido

🧾 3. Factura o boleta

👩‍💼 4. Atención de agente

Responda con el número de la opción deseada`;

                await message.reply(menuMsg);
                await saveMessage(sender, menuMsg, true, message.id._serialized + "_menu", null);
                io.emit("messageSent", {
                    to: sender,
                    message: menuMsg,
                    id: message.id._serialized + "_menu",
                    timestamp: new Date(),
                });
                return;
            }

            if (content === "1") {
                const searchMsg = `🔍 ¡Vamos a buscar tu producto!

📝 Por favor escribe el nombre o descripción del producto que estás buscando.

💡 Tip: Sé lo más específico posible para ayudarte mejor.

⚡ Ejemplo: "Zapatillas Nike Air Max negras talla 42"`;

                await message.reply(searchMsg);
                await saveMessage(sender, searchMsg, true, message.id._serialized + "_search", null);
                io.emit("messageSent", {
                    to: sender,
                    message: searchMsg,
                    id: message.id._serialized + "_search",
                    timestamp: new Date(),
                });
                return;
            }

            if (content === "2") {
                const orderMsg = `📦 ¡Vamos a revisar el estado de tu pedido!

Por favor proporciona los siguientes datos:

👤 Nombre completo
📧 Email asociado a la compra

✍️ Ejemplo: "Juan Pérez, juan@email.com"

⚠️ Importante: Escribe los datos separados por coma`;

                await message.reply(orderMsg);
                await saveMessage(sender, orderMsg, true, message.id._serialized + "_order", null);
                io.emit("messageSent", {
                    to: sender,
                    message: orderMsg,
                    id: message.id._serialized + "_order",
                    timestamp: new Date(),
                });
                return;
            }

            if (content === "3") {
                const invoiceMsg = `🧾 ¡Te ayudaremos con tu documento tributario!

Por favor proporciona:

📌 Tu RUT (sin puntos y con guión)
📅 Fecha de compra (opcional)

✍️ Ejemplo: "12345678-9, 01/12/2024"

⚠️ Nota: El RUT debe coincidir con el registrado en la compra`;

                await message.reply(invoiceMsg);
                await saveMessage(sender, invoiceMsg, true, message.id._serialized + "_invoice", null);
                io.emit("messageSent", {
                    to: sender,
                    message: invoiceMsg,
                    id: message.id._serialized + "_invoice",
                    timestamp: new Date(),
                });
                return;
            }

            if (content === "4") {
                const agentMsg = `👩‍💼 ¡Gracias por contactar a nuestro equipo de atención!

📝 Tu solicitud ha sido registrada con éxito

⏰ Un agente se pondrá en contacto contigo en los próximos minutos

⚡ Horario de atención:
📅 Lunes a Viernes
🕙 9:00 - 18:00 hrs

💡 Mientras esperas, puedes escribir tu consulta para que nuestro agente pueda ayudarte mejor`;

                await message.reply(agentMsg);
                await saveMessage(sender, agentMsg, true, message.id._serialized + "_agent", "pending");
                io.emit("messageSent", {
                    to: sender,
                    message: agentMsg,
                    id: message.id._serialized + "_agent",
                    timestamp: new Date(),
                });
                return;
            }

            if (content.length > 0) {
                const [products] = await connection.execute(
                    "SELECT name, price FROM product_flat WHERE name LIKE ?",
                    [`%${content}%`]
                );

                if (products.length > 0) {
                    let response = "Productos encontrados:\n";
                    products.forEach((product) => {
                        response += `${product.name} - Precio: ${product.price}\n`;
                    });
                    await message.reply(response);
                    await saveMessage(sender, response, true, message.id._serialized + "_products", null);
                    io.emit("messageSent", {
                        to: sender,
                        message: response,
                        id: message.id._serialized + "_products",
                        timestamp: new Date(),
                    });
                    return;
                }
            }

            if (content.includes(",")) {
                const [name, email] = content.split(",").map((item) => item.trim());
                const [orders] = await connection.execute(
                    "SELECT * FROM orders WHERE customer_email = ? AND customer_first_name = ?",
                    [email, name]
                );

                if (orders.length > 0) {
                    let response = "Pedidos encontrados:\n";
                    orders.forEach((order) => {
                        response += `Pedido #${order.id} - Estado: ${order.status}\n`;
                    });
                    await message.reply(response);
                    await saveMessage(sender, response, true, message.id._serialized + "_orders", null);
                    io.emit("messageSent", {
                        to: sender,
                        message: response,
                        id: message.id._serialized + "_orders",
                        timestamp: new Date(),
                    });
                } else {
                    const noOrdersMsg = "No hay pedidos bajo ese cliente.";
                    await message.reply(noOrdersMsg);
                    await saveMessage(sender, noOrdersMsg, true, message.id._serialized + "_no_orders", null);
                    io.emit("messageSent", {
                        to: sender,
                        message: noOrdersMsg,
                        id: message.id._serialized + "_no_orders",
                        timestamp: new Date(),
                    });
                }
                return;
            }

            const [invoices] = await connection.execute(
                "SELECT * FROM invoices WHERE rut = ?",
                [content]
            );

            if (invoices.length > 0) {
                let response = "Facturas encontradas:\n";
                invoices.forEach((invoice) => {
                    response += `Factura #${invoice.id}\n`;
                });
                await message.reply(response);
                await saveMessage(sender, response, true, message.id._serialized + "_invoices", null);
                io.emit("messageSent", {
                    to: sender,
                    message: response,
                    id: message.id._serialized + "_invoices",
                    timestamp: new Date(),
                });
                return;
            }

            const defaultMsg = 'No se encontró información. Escriba "menu" o "0" para ver las opciones disponibles.';
            await message.reply(defaultMsg);
            await saveMessage(sender, defaultMsg, true, message.id._serialized + "_default", null);
            io.emit("messageSent", {
                to: sender,
                message: defaultMsg,
                id: message.id._serialized + "_default",
                timestamp: new Date(),
            });

        } catch (error) {
            console.error("Database query error:", error);
            const errorMsg = 'Por favor escriba "menu" o "0" para ver las opciones disponibles.';
            await message.reply(errorMsg);
            await saveMessage(sender, errorMsg, true, message.id._serialized + "_error", null);
            io.emit("messageSent", {
                to: sender,
                message: errorMsg,
                id: message.id._serialized + "_error",
                timestamp: new Date(),
            });
        } finally {
            await connection.end();
        }
    } catch (error) {
        console.error("Message handling error:", error);
    }
});

client.on("disconnected", (reason) => {
    console.log("WhatsApp client was disconnected", reason);
    isAuthenticated = false;
    isClientInitialized = false;
    currentQR = null;
    io.emit("redirectToTemplates", {
        authenticated: false,
        redirectUrl: "/admin/whatsapp/templates",
    });
});

io.on("connection", (socket) => {
    console.log("Client connected to WebSocket");

    if (currentQR) {
        socket.emit("qrCode", currentQR);
    }

    if (isAuthenticated) {
        handleAuthentication(socket);
    }

    socket.on("checkAuth", () => {
        handleAuthentication(socket);
    });

    socket.on("getMessages", async (phoneNumber) => {
        const messages = await getMessageHistory(phoneNumber);
        socket.emit("messageHistory", messages);
    });

    socket.on("disconnect", () => {
        console.log("Client disconnected from WebSocket");
    });
});

app.get("/admin/whatsapp/templates", (req, res) => {
    if (isAuthenticated) {
        res.redirect(REDIRECT_URL);
        return;
    }
    res.sendFile(
        __dirname +
        "/packages/Webkul/Admin/src/Resources/views/whatsapp/templates/index.blade.php"
    );
});

app.get("/admin/whatsapp/columns-atention", (req, res) => {
    if (!isAuthenticated) {
        res.redirect("/admin/whatsapp/templates");
        return;
    }
    res.sendFile(
        __dirname +
        "/packages/Webkul/Admin/src/Resources/views/whatsapp/columns/atention.blade.php"
    );
 });

 app.get("/admin/whatsapp/messages", (req, res) => {
    if (!isAuthenticated) {
        res.redirect("/admin/whatsapp/templates");
        return;
    }
    res.sendFile(
        __dirname +
        "/packages/Webkul/Admin/src/Resources/views/whatsapp/messages/index.blade.php"
    );
 });

 app.get("/auth/status", (req, res) => {
    res.json({ isAuthenticated });
 });

 // Endpoint para enviar mensajes
 app.post("/send-message", async (req, res) => {
    try {
        if (!isAuthenticated) {
            return res.status(401).json({ error: "No autenticado" });
        }

        const { number, message } = req.body;
        await client.sendMessage(number, message);

        res.json({ success: true });
    } catch (error) {
        console.error("Error sending message:", error);
        res.status(500).json({ error: "Error al enviar mensaje" });
    }
 });

 // Manejo de errores global
 app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).send('¡Algo salió mal!');
 });

 server.listen(3000, () => {
    console.log("Server running on port 3000");
 });

 // Manejo de cierre graceful
 process.on("SIGINT", async () => {
    console.log("Cerrando servidor y conexiones...");
    try {
        await client.destroy();
        server.close(() => {
            console.log("Servidor cerrado");
            process.exit(0);
        });
    } catch (error) {
        console.error("Error durante el cierre:", error);
        process.exit(1);
    }
 });

 // Manejo de excepciones no capturadas
 process.on('uncaughtException', (err) => {
    console.error('Excepción no capturada:', err);
 });

 process.on('unhandledRejection', (reason, promise) => {
    console.error('Rechazo no manejado en:', promise, 'razón:', reason);
 });

 // Inicialización del cliente
 client.initialize();
 isClientInitialized = true;
