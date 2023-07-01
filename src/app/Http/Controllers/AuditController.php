<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Models\User;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->user_id;
        $user_id = $request->user_id;
        $event = $request->event;
        $auditable_type = $request->auditable_type;
        $audits = Audit::query();
        if ($user_id) {
            $audits = $audits->where('user_id', $user_id);
        }
        if ($event) {
            $audits = $audits->where('event', $event);
        }
        if ($auditable_type) {
            $audits = $audits->where('auditable_type', $auditable_type);
        }
        $events = Audit::getEvents();
        $auditable_types = Audit::getAuditableTypes();
        $audits = $audits->orderBy('id', 'desc')->paginate();
        $users = User::all();
        $request->flash();
        return view('backend.audit.index', compact('audits', 'users', 'events', 'auditable_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
