<?php

namespace App\Repositories;

use App\Models\Attachment;
use App\Repositories\Interfaces\AttachmentRepositoryInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachmentRepository extends BaseRepository implements AttachmentRepositoryInterface {
    protected $model;
    protected $disk = 's3';

    public function __construct(Attachment $model)
    {
        parent::__construct($model);
        $this->model = $model;
    }

    /**
     * @param $imageName
     * @return string
     */
    public function retrieveAttachmentUrl($imageName)
    {
        return Storage::disk($this->disk)->url($imageName);
    }

    /**
     * @param $attachment
     * @param string $folderName
     * @return string
     * @throws \Exception
     */
    public function uploadToDrive($attachment, $folderName = "attachments")
    {
        $randomlyGeneratedName = Str::uuid();
        $attachmentNameWithDir = "${folderName}/$randomlyGeneratedName";

        try {
            $fileUploaded = Storage::disk($this->disk)->put($attachmentNameWithDir, $attachment, 'public');
            if ($fileUploaded) {
                return $this->retrieveAttachmentUrl($attachmentNameWithDir);
            } else {
                throw new \Exception("Image could not be uploaded!");
            }
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param $attachmentUrl
     * @param $attachmentType
     * @param $attachmentId
     * @return mixed
     */
    public function storeAttachment($attachmentUrl, $attachmentType, $attachmentId)
    {
        return $this->model->create([
            'url' => $attachmentUrl,
            'attachable_type' => $attachmentType,
            'attachable_id' => $attachmentId
        ]);
    }

    /**
     * @param $attachmentUrl
     * @param $attachmentType
     * @param $attachmentId
     * @return mixed
     */
    public function storeAttachmentsFromFileRequest($attachments, $attachmentType, $attachmentId)
    {
        $mappedAttachments = array_map(function ($file) use ($attachmentType, $attachmentId) {
            return [
                'name' => $file['name'],
                "attachable_type" => $attachmentType,
                "attachable_id" => $attachmentId,
                "url" => $file['url'],
                "size" => formatSizeUnits($file['size'])
            ];
        }, $attachments);

        return $this->model->insert($mappedAttachments);
    }

    public function retrieveAttachment(): Attachment
    {
        // TODO: Implement retrieveAttachment() method.
    }
}
