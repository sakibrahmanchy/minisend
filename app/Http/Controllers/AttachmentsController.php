<?php

namespace App\Http\Controllers;

use App\Repositories\AttachmentRepository;
use Illuminate\Http\Request;

class AttachmentsController extends Controller
{
    protected $attachmentRepo;
    public function __construct(AttachmentRepository $attachmentRepository)
    {
        $this->attachmentRepo = $attachmentRepository;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file',
        ]);
        $file = fopen($request->file, 'r+');

        try {
            $imageUrl = $this->attachmentRepo->uploadToDrive($file);
            return response()->json([
                'success' => true,
                'url' => $imageUrl
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => "Could not upload files, internal server error!"
            ], 500);
        }
    }
}
