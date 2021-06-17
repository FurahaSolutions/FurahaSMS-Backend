<?php


namespace Okotieno\Files\Traits;


use Okotieno\Files\Models\ProfilePic;

trait HasProfilePics
{
  public function saveProfilePic($request)
  {
    $this->profilePics()->create([
      'model' => get_class($this),
      'file_document_id' => $request->profile_pic_id
    ]);
  }

  public function profilePics()
  {
    return $this->hasMany(ProfilePic::class, 'model_id')
      ->where('profile_pics.model', '=', get_class($this));;
  }

  public function getProfilePicIdAttribute()
  {
    return $this->profilePics->last() ? $this->profilePics->last()->file_document_id : null;
  }
}
