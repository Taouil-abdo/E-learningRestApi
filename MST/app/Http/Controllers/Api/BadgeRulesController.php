<?php

namespace App\Http\Controllers\Api;

use App\Models\badge_rules;
use App\Http\Requests\Storebadge_rulesRequest;
use App\Http\Requests\Updatebadge_rulesRequest;

class BadgeRulesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Storebadge_rulesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(badge_rules $badge_rules)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(badge_rules $badge_rules)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatebadge_rulesRequest $request, badge_rules $badge_rules)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(badge_rules $badge_rules)
    {
        //
    }
}
