<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait HasSingleFileUpload
{
    public $uploadedFile;

    public $selection;

    public $existingImage;

    // DEBUG

    // public $ufWasSet = false;
    // BASE 36 from QRID
    // user id + 78364164096


    public function updatedSelection($value, $key)
    {
        $this->validate([
            'selection' => 'image|max:5120',
        ]);

        $this->uploadedFile = $this->selection;
        // $this->ufWasSet = true;
        // if ($this->ufWasSet && !$this->uploadedFile){
        //     ddd('kaputt');
        // }
    }

    public function storeAvatar(User $user)
    {
        if (!$this->uploadedFile) {
            return false;
        }

        $this->uploadedFile->store('avatars');
        $path = 'avatars/' . $this->uploadedFile->hashName();
        $user->avatar = $path;
        $user->save();

        //return true indicates we need a page reload
        return true;
    }

    public function storeAvatarWith($userId)
    {
        if (!$this->uploadedFile) {
            return;
        }
        // ddd(['store' => "company/$companyId/media"]);
        // $this->uploadedFile->store('public/companies/');
        // $path = 'companies/' . $this->uploadedFile->hashName();
        $this->uploadedFile->store('avatars');
        $path = 'avatars/' . $this->uploadedFile->hashName();
        $this->currentUser->avatar = $path;

        $this->currentUser->save();
    }

    public function storeLogoWith($companyId)
    {
        if (!$this->uploadedFile) {
            return;
        }
        // ddd(['store' => "company/$companyId/media"]);
        $this->uploadedFile->store('companies');
        $path = 'companies/' . $this->uploadedFile->hashName();
        $this->currentCompany->logo = $path;
        // ddd($path);
        $this->currentCompany->logoFile()->create([
            'filetype' => 'image',
            'path' => $path,
            'filename' => $this->uploadedFile->hashName(),
            'company_id' => $companyId
        ]);

        $this->currentCompany->save();
    }

    public function deleteFile($id)
    {
        unset($this->uploadedFile);
    }
}
