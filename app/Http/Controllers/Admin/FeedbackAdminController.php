<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;

class FeedbackAdminController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::with('user')->latest()->paginate(20);
        $avgRating = round(Feedback::avg('rating'), 1);
        $ratingCounts = Feedback::selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->orderBy('rating')
            ->pluck('total', 'rating');

        return view('admin.feedback.index', compact('feedbacks', 'avgRating', 'ratingCounts'));
    }
}
