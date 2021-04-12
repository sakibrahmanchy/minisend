<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMailRequest;
use App\Jobs\ProcessMail;
use App\Models\Mail;
use App\Repositories\AttachmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;

class MailController extends Controller
{
    protected $attachmentRepo;
    public function __construct(AttachmentRepository $attachmentRepository)
    {
        $this->attachmentRepo = $attachmentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit');
        $search = $request->input('search');
        $status = $request->input('status');
        $to = $request->input('to');

        $paginator = Mail::select(['id', 'from', 'to', 'subject', 'created_at', 'status', 'failure_reason'])
            ->where(function ($query) use ($search) {
                $query->where('from', 'like', "%$search%")
                    ->orWhere('to', 'like', "%$search%")
                    ->orWhere('subject', 'like', "%$search%");
            });

        if (is_string($status)) {
            $paginator = $paginator->where('status', $status);
        }

        if ($to) {
            $paginator = $paginator->where('to', $to);
        }


        $paginator = $paginator
            ->where('created_by', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate($limit)->withQueryString();

        return  response()->json([
            'success' => true,
            'data' => $paginator,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateMailRequest $mailRequest)
    {
        $mail = Mail::create(array_merge(
            $mailRequest->except('content'),
            ['content' => Purify::clean($mailRequest->get('content'))]
        ));

        if ($mailRequest->get('files')) {
            $this->attachmentRepo->storeAttachmentsFromFileRequest(
                $mailRequest->get('files'),
                Mail::class,
                $mail->id);
        }

        ProcessMail::dispatch($mail);

        return response()->json([
            'success' => true,
            'message' => 'Mail successfully created',
            'data' => $mail,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return response()->json([
            'success' => true,
            'data' => Mail::with('attachments')->findOrFail($id),
        ], 200);
    }

    public function resend($id) {
        $mail = Mail::findOrFail($id);

        ProcessMail::dispatch($mail);

        return response()->json([
            'success' => true,
            'message' => 'Mail reset request successful',
            'data' => $mail,
        ], 200);
    }
}
