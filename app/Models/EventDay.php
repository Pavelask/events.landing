<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;use Illuminate\Database\Eloquent\Relations\BelongsTo;use Illuminate\Database\Eloquent\Relations\HasMany;
class EventDay extends Model{protected $fillable=['event_id','date','label','description','sort_order','is_active'];protected function casts():array{return['date'=>'date','is_active'=>'boolean'];}public function event():BelongsTo{return $this->belongsTo(Event::class);}public function events():HasMany{return $this->hasMany(ScheduleEvent::class)->orderBy('start_time')->orderBy('sort_order');}}
