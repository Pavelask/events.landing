<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;use Illuminate\Database\Eloquent\Relations\BelongsTo;
class EventSpeaker extends Model{protected $table='event_speaker';protected $fillable=['event_id','speaker_id','is_keynote','sort_order'];protected function casts():array{return['is_keynote'=>'boolean'];}public function event():BelongsTo{return $this->belongsTo(Event::class);}public function speaker():BelongsTo{return $this->belongsTo(Speaker::class);}}
