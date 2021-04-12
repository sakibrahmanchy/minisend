<?php
namespace App\Repositories\Interfaces;

use App\Models\Attachment;

interface AttachmentRepositoryInterface extends EloquentRepositoryInterface {
    public function uploadToDrive($attachment, $folder = 'attachments');
    public function storeAttachment($attachmentUrl, $attachmentType, $attachmentId);
    public function storeAttachmentsFromFileRequest($attachments, $attachmentType, $attachmentId);
    public function retrieveAttachment(): Attachment;
}
