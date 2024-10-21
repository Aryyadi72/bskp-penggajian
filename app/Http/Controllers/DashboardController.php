<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $roles = $request->get('roles');
        // dd($roles);
        if ($request->query('token')) {
            $token = $request->query('token');
            $request->session()->put('jwt_token', $token);
        }

        $token = $request->query('token');
        // $statuses = User::select('status')->where('status', 'Manager')->groupBy('status')->pluck('status')->count();
        $managerCount = User::where('status', 'Manager')->count();
        $staffCount = User::where('status', 'Staff')->count();
        $monthlyCount = User::where('status', 'Monthly')->count();
        $regularCount = User::where('status', 'Regular')->count();
        $contractBskpCount = User::where('status', 'Contract BSKP')->count();
        $contractFlCount = User::where('status', 'Contract FL')->count();

        return view('dashboard.index', [
            'title' => 'Dashboard',
            'managerCount' => $managerCount,
            'staffCount' => $staffCount,
            'monthlyCount' => $monthlyCount,
            'regularCount' => $regularCount,
            'contractBskpCount' => $contractBskpCount,
            'contractFlCount' => $contractFlCount,
            'roles' => $roles
        ]);
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
