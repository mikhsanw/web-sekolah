<?php

namespace App\Http\Controllers\Backend;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = $request->user();
            $data = Activity::query();
            return datatables()
                ->of($data)

                ->editColumn('causer', function (Activity $activity) {

                    if (!$activity->causer) {
                        return '<span class="text-muted">System</span>';
                    }

                    $name  = e($activity->causer->name);
                    $email = e($activity->causer->email);

                    // Avatar base64 image
                    $avatar = app(\Laravolt\Avatar\Avatar::class)
                        ->create($activity->causer->name)
                        ->toBase64();


                    return '
                        <div class="d-flex align-items-center">
                            <img src="' . $avatar . '" 
                                class="rounded-circle me-2" 
                                width="40" height="40">

                            <div>
                                <div class="fw-semibold">' . $name . '</div>
                                <div class="text-muted small">' . $email . '</div>
                            </div>
                        </div>';
                })

                ->editColumn('event', function (Activity $activity) {

                    $event = $activity->event ?? 'unknown';

                    $color = match ($event) {
                        'created' => 'success',
                        'updated' => 'primary',
                        'deleted' => 'danger',
                        default   => 'secondary'
                    };

                    return '<span class="badge bg-' . $color . ' text-uppercase">' . e($event) . '</span>';
                })

                ->addColumn('subject', function (Activity $activity) {
                    if (!$activity->subject_type) {
                        return '<span class="text-muted">N/A</span>';
                    }

                    return class_basename($activity->subject_type)
                        . '<br><small class="text-muted">#'
                        . e($activity->subject_id)
                        . '</small>';
                })

                ->addColumn('action', function ($data) use ($user) {

                    $buttons = '';

                    // View
                    $buttons .= '
                    <button type="button"
                        class="btn btn-sm btn-light-primary btn-action"
                        data-title="Detail"
                        data-action="show"
                        data-url="' . $this->url . '"
                        data-id="' . $data->id . '"
                        title="View">
                        <i class="fa fa-eye text-info"></i>
                    </button>';

                    return '<div class="btn-group">' . $buttons . '</div>';
                })

                ->addIndexColumn()
                ->rawColumns(['causer', 'event', 'subject', 'action'])
                ->make(true);
        }
        return view($this->view . '.index');
    }

    public function show($id)
    {
        $data = Activity::find($id);
        return view($this->view . '.show', compact('data'));
    }
}
