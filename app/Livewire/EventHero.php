<?php
namespace App\Livewire;
use Livewire\Component;
class EventHero extends Component{public ?\App\Models\Event $event=null; public $slides; public function mount(?\App\Models\Event $event=null): void{$this->event=$event ?? \App\Models\Event::active()->with('heroSlides')->first() ?? \App\Models\Event::upcoming()->with('heroSlides')->first();$this->slides=$this->event?->heroSlides()->where('is_active',true)->get() ?? collect();} public function render(){return view('livewire.event-hero');}}
