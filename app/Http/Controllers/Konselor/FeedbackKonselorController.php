<?php

namespace App\Http\Controllers\Konselor;

use App\Http\Controllers\Controller;
use App\Models\Feedback;

class FeedbackKonselorController extends Controller
{
    public function index()
    {
        $feedbacks  = Feedback::with('user')->latest()->paginate(20);
        $avgRating  = round(Feedback::avg('rating'), 1);
        $ratingCounts = Feedback::selectRaw('rating, count(*) as total')
            ->groupBy('rating')
            ->orderBy('rating')
            ->pluck('total', 'rating');
        return view('konselor.feedback.index', compact('feedbacks', 'avgRating', 'ratingCounts'));
    }
}
