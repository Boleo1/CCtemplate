<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Events;
use Illuminate\Http\Request;
use Carbon\CarbonImmutable;
class eventFeedController extends Controller
{
public function index(Request $request)
{
    $events = Events::orderBy('start_at')->get();

    return response()->json(
        $events->map(function ($e) {
            $allDay = (bool) $e->all_day;

            $start = $e->start_at?->toIso8601String();
            $end   = $e->end_at?->toIso8601String();

            if ($allDay && $e->end_at) {
                $end = $e->end_at->copy()->addDay()->startOfDay()->toIso8601String();
            }

            $out = [
                'id'     => (string) $e->id,
                'title'  => $e->title,
                'start'  => $start,
                'allDay' => $allDay,
                'url'    => route('events.show',$e->slug),
                'extendedProps' => [
                    'event_type'  => $e->event_type,
                    'status'      => $e->status,
                    'visibility'  => $e->visibility,
                    'description' => $e->description,
                ],
            ];
            if ($end) { $out['end'] = $end; }
            return $out;
        })
    );

if (! function_exists('schema_has_column')) {
    function schema_has_column(string $table, string $column): bool {
        try { return \Illuminate\Support\Facades\Schema::hasColumn($table, $column); }
        catch (\Throwable $e) { return false; }
    }

  }}
}