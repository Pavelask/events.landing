<?php
namespace App\Livewire;
use Livewire\Component;
class CountdownTimer extends Component{public ?\App\Models\Event $event=null; public ?string $startDate=null; public function mount(?\App\Models\Event $event=null): void{$this->event=$event ?? \App\Models\Event::upcoming()->first();if($this->event && $this->event->start_date->isFuture()){$this->startDate=$this->event->start_date->toIso8601String();}} public function render(){return view('livewire.countdown-timer');}}
