<?php

namespace Webkul\Admin\Http\Controllers;

class WhatsappController extends Controller
{
    /**
     * Display a listing of whatsapp configurations.
     */
    public function index()
    {
        return view('admin::whatsapp.index');
    }

    public function templates()
    {
        return view('admin::whatsapp.templates.index');
    }

    public function messages()
    {
        return view('admin::whatsapp.messages.index');
    }

    public function columnsAttention() {
        return view('admin::whatsapp.columns.atention');
    }

}
