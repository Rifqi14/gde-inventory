<?php

namespace App\Models\Transmittal\Outcoming;

use Alfa6661\AutoNumber\AutoNumberTrait;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class OutcomingTransmittal extends Model
{
    use AutoNumberTrait;

    public function getAutoNumberOptions()
    {
        $year   = date('Y');
        $tab    = request('tab');
        return [
            'temp_transmittal_no'    => [
                'format'        => "?-TRE-$tab-$year",
                'length'        => 3,
            ]
        ];
    }

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Undocumented function
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function contractorgroup()
    {
        return $this->belongsTo(Role::class, 'contractor_group_id', 'id');
    }

    /**
     * Undocumented function
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function issuedby()
    {
        return $this->belongsTo(\App\User::class, 'issued_by', 'id');
    }

    /**
     * Undocumented function
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function senderuploader()
    {
        return $this->belongsTo(\App\User::class, 'sender_signed_copy_uploaded_by', 'id');
    }

    /**
     * Undocumented function
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function recipientuploader()
    {
        return $this->belongsTo(\App\User::class, 'recipient_signed_copy_uploaded_by', 'id');
    }

    /**
     * Undocumented function
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function attentions()
    {
        return $this->belongsToMany(\App\User::class, 'attention_outcoming_transmittals', 'outcoming_transmittal_id', 'user_id');
    }

    /**
     * Undocumented function
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function ccs()
    {
        return $this->belongsToMany(\App\Models\Role::class, 'cc_outcoming_transmittals', 'outcoming_transmittal_id', 'role_id');
    }

    /**
     * Undocumented function
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function documents()
    {
        return $this->belongsToMany(\App\Models\DocExternal\DocumentExternalRevision::class, 'document_outcoming_transmittals', 'outcoming_transmittal_id', 'revision_id');
    }
}
