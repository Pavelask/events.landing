// GENERATED CODE - DO NOT MODIFY BY HAND
// coverage:ignore-file
// ignore_for_file: type=lint, type=warning, deprecated_member_use, deprecated_member_use_from_same_package
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'schedule_event.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

// GENERATED CODE - DO NOT MODIFY BY HAND
// dart format off
T _$identity<T>(T value) => value;

/// @nodoc
mixin _$ScheduleEvent {

 int get id; String? get startTime; String? get endTime; String? get title; String? get description; String? get location; bool get isBreak; SpeakerBrief? get speaker;
/// Create a copy of ScheduleEvent
/// with the given fields replaced by the non-null parameter values.
@JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
$ScheduleEventCopyWith<ScheduleEvent> get copyWith => _$ScheduleEventCopyWithImpl<ScheduleEvent>(this as ScheduleEvent, _$identity);

  /// Serializes this ScheduleEvent to a JSON map.
  Map<String, dynamic> toJson();


@override
bool operator ==(Object other) {
  final _this = this as ScheduleEvent;
  return identical(this, other) || (other.runtimeType == runtimeType&&other is ScheduleEvent&&(identical(other.id, _this.id) || other.id == _this.id)&&(identical(other.startTime, _this.startTime) || other.startTime == _this.startTime)&&(identical(other.endTime, _this.endTime) || other.endTime == _this.endTime)&&(identical(other.title, _this.title) || other.title == _this.title)&&(identical(other.description, _this.description) || other.description == _this.description)&&(identical(other.location, _this.location) || other.location == _this.location)&&(identical(other.isBreak, _this.isBreak) || other.isBreak == _this.isBreak)&&(identical(other.speaker, _this.speaker) || other.speaker == _this.speaker));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
  final _this = this as ScheduleEvent;
  return Object.hash(runtimeType,_this.id,_this.startTime,_this.endTime,_this.title,_this.description,_this.location,_this.isBreak,_this.speaker);
}

@override
String toString() {
  final _this = this as ScheduleEvent;
  return 'ScheduleEvent(id: ${_this.id}, startTime: ${_this.startTime}, endTime: ${_this.endTime}, title: ${_this.title}, description: ${_this.description}, location: ${_this.location}, isBreak: ${_this.isBreak}, speaker: ${_this.speaker})';
}


}

/// @nodoc
abstract mixin class $ScheduleEventCopyWith<$Res>  {
  factory $ScheduleEventCopyWith(ScheduleEvent value, $Res Function(ScheduleEvent) _then) = _$ScheduleEventCopyWithImpl;
@useResult
$Res call({
 int id, String? startTime, String? endTime, String? title, String? description, String? location, bool isBreak, SpeakerBrief? speaker
});


$SpeakerBriefCopyWith<$Res>? get speaker;

}
/// @nodoc
class _$ScheduleEventCopyWithImpl<$Res>
    implements $ScheduleEventCopyWith<$Res> {
  _$ScheduleEventCopyWithImpl(this._self, this._then);

  final ScheduleEvent _self;
  final $Res Function(ScheduleEvent) _then;

/// Create a copy of ScheduleEvent
/// with the given fields replaced by the non-null parameter values.
@pragma('vm:prefer-inline') @override $Res call({Object? id = null,Object? startTime = freezed,Object? endTime = freezed,Object? title = freezed,Object? description = freezed,Object? location = freezed,Object? isBreak = null,Object? speaker = freezed,}) {
  return _then(ScheduleEvent(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,startTime: freezed == startTime ? _self.startTime : startTime // ignore: cast_nullable_to_non_nullable
as String?,endTime: freezed == endTime ? _self.endTime : endTime // ignore: cast_nullable_to_non_nullable
as String?,title: freezed == title ? _self.title : title // ignore: cast_nullable_to_non_nullable
as String?,description: freezed == description ? _self.description : description // ignore: cast_nullable_to_non_nullable
as String?,location: freezed == location ? _self.location : location // ignore: cast_nullable_to_non_nullable
as String?,isBreak: null == isBreak ? _self.isBreak : isBreak // ignore: cast_nullable_to_non_nullable
as bool,speaker: freezed == speaker ? _self.speaker : speaker // ignore: cast_nullable_to_non_nullable
as SpeakerBrief?,
  ));
}
/// Create a copy of ScheduleEvent
/// with the given fields replaced by the non-null parameter values.
@override
@pragma('vm:prefer-inline')
$SpeakerBriefCopyWith<$Res>? get speaker {
    if (_self.speaker == null) {
    return null;
  }

  return $SpeakerBriefCopyWith<$Res>(_self.speaker!, (value) {
    return _then(_self.copyWith(speaker: value));
  });
}
}


/// Adds pattern-matching-related methods to [ScheduleEvent].
extension ScheduleEventPatterns on ScheduleEvent {
/// A variant of `map` that fallback to returning `orElse`.
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case final Subclass value:
///     return ...;
///   case _:
///     return orElse();
/// }
/// ```

@optionalTypeArgs TResult maybeMap<TResult extends Object?>(TResult Function( _ScheduleEvent value)?  $default,{required TResult orElse(),}){
final _that = this;
switch (_that) {
case _ScheduleEvent() when $default != null:
return $default(_that);case _:
  return orElse();

}
}
/// A `switch`-like method, using callbacks.
///
/// Callbacks receives the raw object, upcasted.
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case final Subclass value:
///     return ...;
///   case final Subclass2 value:
///     return ...;
/// }
/// ```

@optionalTypeArgs TResult map<TResult extends Object?>(TResult Function( _ScheduleEvent value)  $default,){
final _that = this;
switch (_that) {
case _ScheduleEvent():
return $default(_that);case _:
  throw StateError('Unexpected subclass');

}
}
/// A variant of `map` that fallback to returning `null`.
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case final Subclass value:
///     return ...;
///   case _:
///     return null;
/// }
/// ```

@optionalTypeArgs TResult? mapOrNull<TResult extends Object?>(TResult? Function( _ScheduleEvent value)?  $default,){
final _that = this;
switch (_that) {
case _ScheduleEvent() when $default != null:
return $default(_that);case _:
  return null;

}
}
/// A variant of `when` that fallback to an `orElse` callback.
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case Subclass(:final field):
///     return ...;
///   case _:
///     return orElse();
/// }
/// ```

@optionalTypeArgs TResult maybeWhen<TResult extends Object?>(TResult Function( int id,  String? startTime,  String? endTime,  String? title,  String? description,  String? location,  bool isBreak,  SpeakerBrief? speaker)?  $default,{required TResult orElse(),}) {final _that = this;
switch (_that) {
case _ScheduleEvent() when $default != null:
return $default(_that.id,_that.startTime,_that.endTime,_that.title,_that.description,_that.location,_that.isBreak,_that.speaker);case _:
  return orElse();

}
}
/// A `switch`-like method, using callbacks.
///
/// As opposed to `map`, this offers destructuring.
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case Subclass(:final field):
///     return ...;
///   case Subclass2(:final field2):
///     return ...;
/// }
/// ```

@optionalTypeArgs TResult when<TResult extends Object?>(TResult Function( int id,  String? startTime,  String? endTime,  String? title,  String? description,  String? location,  bool isBreak,  SpeakerBrief? speaker)  $default,) {final _that = this;
switch (_that) {
case _ScheduleEvent():
return $default(_that.id,_that.startTime,_that.endTime,_that.title,_that.description,_that.location,_that.isBreak,_that.speaker);case _:
  throw StateError('Unexpected subclass');

}
}
/// A variant of `when` that fallback to returning `null`
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case Subclass(:final field):
///     return ...;
///   case _:
///     return null;
/// }
/// ```

@optionalTypeArgs TResult? whenOrNull<TResult extends Object?>(TResult? Function( int id,  String? startTime,  String? endTime,  String? title,  String? description,  String? location,  bool isBreak,  SpeakerBrief? speaker)?  $default,) {final _that = this;
switch (_that) {
case _ScheduleEvent() when $default != null:
return $default(_that.id,_that.startTime,_that.endTime,_that.title,_that.description,_that.location,_that.isBreak,_that.speaker);case _:
  return null;

}
}

}

/// @nodoc
@JsonSerializable()

class _ScheduleEvent implements ScheduleEvent {
  const _ScheduleEvent({required this.id, this.startTime, this.endTime, this.title, this.description, this.location, this.isBreak = false, this.speaker});
  factory _ScheduleEvent.fromJson(Map<String, dynamic> json) => _$ScheduleEventFromJson(json);

@override final  int id;
@override final  String? startTime;
@override final  String? endTime;
@override final  String? title;
@override final  String? description;
@override final  String? location;
@override@JsonKey() final  bool isBreak;
@override final  SpeakerBrief? speaker;

/// Create a copy of ScheduleEvent
/// with the given fields replaced by the non-null parameter values.
@override @JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
_$ScheduleEventCopyWith<_ScheduleEvent> get copyWith => __$ScheduleEventCopyWithImpl<_ScheduleEvent>(this, _$identity);

@override
Map<String, dynamic> toJson() {
  return _$ScheduleEventToJson(this, );
}

@override
bool operator ==(Object other) {
    return identical(this, other) || (other.runtimeType == runtimeType&&other is _ScheduleEvent&&(identical(other.id, id) || other.id == id)&&(identical(other.startTime, startTime) || other.startTime == startTime)&&(identical(other.endTime, endTime) || other.endTime == endTime)&&(identical(other.title, title) || other.title == title)&&(identical(other.description, description) || other.description == description)&&(identical(other.location, location) || other.location == location)&&(identical(other.isBreak, isBreak) || other.isBreak == isBreak)&&(identical(other.speaker, speaker) || other.speaker == speaker));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
    return Object.hash(runtimeType,id,startTime,endTime,title,description,location,isBreak,speaker);
}

@override
String toString() {
    return 'ScheduleEvent(id: $id, startTime: $startTime, endTime: $endTime, title: $title, description: $description, location: $location, isBreak: $isBreak, speaker: $speaker)';
}


}

/// @nodoc
abstract mixin class _$ScheduleEventCopyWith<$Res> implements $ScheduleEventCopyWith<$Res> {
  factory _$ScheduleEventCopyWith(_ScheduleEvent value, $Res Function(_ScheduleEvent) _then) = __$ScheduleEventCopyWithImpl;
@override @useResult
$Res call({
 int id, String? startTime, String? endTime, String? title, String? description, String? location, bool isBreak, SpeakerBrief? speaker
});


@override $SpeakerBriefCopyWith<$Res>? get speaker;

}
/// @nodoc
class __$ScheduleEventCopyWithImpl<$Res>
    implements _$ScheduleEventCopyWith<$Res> {
  __$ScheduleEventCopyWithImpl(this._self, this._then);

  final _ScheduleEvent _self;
  final $Res Function(_ScheduleEvent) _then;

/// Create a copy of ScheduleEvent
/// with the given fields replaced by the non-null parameter values.
@override @pragma('vm:prefer-inline') $Res call({Object? id = null,Object? startTime = freezed,Object? endTime = freezed,Object? title = freezed,Object? description = freezed,Object? location = freezed,Object? isBreak = null,Object? speaker = freezed,}) {
  return _then(_ScheduleEvent(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,startTime: freezed == startTime ? _self.startTime : startTime // ignore: cast_nullable_to_non_nullable
as String?,endTime: freezed == endTime ? _self.endTime : endTime // ignore: cast_nullable_to_non_nullable
as String?,title: freezed == title ? _self.title : title // ignore: cast_nullable_to_non_nullable
as String?,description: freezed == description ? _self.description : description // ignore: cast_nullable_to_non_nullable
as String?,location: freezed == location ? _self.location : location // ignore: cast_nullable_to_non_nullable
as String?,isBreak: null == isBreak ? _self.isBreak : isBreak // ignore: cast_nullable_to_non_nullable
as bool,speaker: freezed == speaker ? _self.speaker : speaker // ignore: cast_nullable_to_non_nullable
as SpeakerBrief?,
  ));
}

/// Create a copy of ScheduleEvent
/// with the given fields replaced by the non-null parameter values.
@override
@pragma('vm:prefer-inline')
$SpeakerBriefCopyWith<$Res>? get speaker {
    if (_self.speaker == null) {
    return null;
  }

  return $SpeakerBriefCopyWith<$Res>(_self.speaker!, (value) {
    return _then(_self.copyWith(speaker: value));
  });
}
}


/// @nodoc
mixin _$SpeakerBrief {

 int get id; String get name; String? get position; String? get photo;
/// Create a copy of SpeakerBrief
/// with the given fields replaced by the non-null parameter values.
@JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
$SpeakerBriefCopyWith<SpeakerBrief> get copyWith => _$SpeakerBriefCopyWithImpl<SpeakerBrief>(this as SpeakerBrief, _$identity);

  /// Serializes this SpeakerBrief to a JSON map.
  Map<String, dynamic> toJson();


@override
bool operator ==(Object other) {
  final _this = this as SpeakerBrief;
  return identical(this, other) || (other.runtimeType == runtimeType&&other is SpeakerBrief&&(identical(other.id, _this.id) || other.id == _this.id)&&(identical(other.name, _this.name) || other.name == _this.name)&&(identical(other.position, _this.position) || other.position == _this.position)&&(identical(other.photo, _this.photo) || other.photo == _this.photo));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
  final _this = this as SpeakerBrief;
  return Object.hash(runtimeType,_this.id,_this.name,_this.position,_this.photo);
}

@override
String toString() {
  final _this = this as SpeakerBrief;
  return 'SpeakerBrief(id: ${_this.id}, name: ${_this.name}, position: ${_this.position}, photo: ${_this.photo})';
}


}

/// @nodoc
abstract mixin class $SpeakerBriefCopyWith<$Res>  {
  factory $SpeakerBriefCopyWith(SpeakerBrief value, $Res Function(SpeakerBrief) _then) = _$SpeakerBriefCopyWithImpl;
@useResult
$Res call({
 int id, String name, String? position, String? photo
});




}
/// @nodoc
class _$SpeakerBriefCopyWithImpl<$Res>
    implements $SpeakerBriefCopyWith<$Res> {
  _$SpeakerBriefCopyWithImpl(this._self, this._then);

  final SpeakerBrief _self;
  final $Res Function(SpeakerBrief) _then;

/// Create a copy of SpeakerBrief
/// with the given fields replaced by the non-null parameter values.
@pragma('vm:prefer-inline') @override $Res call({Object? id = null,Object? name = null,Object? position = freezed,Object? photo = freezed,}) {
  return _then(SpeakerBrief(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,name: null == name ? _self.name : name // ignore: cast_nullable_to_non_nullable
as String,position: freezed == position ? _self.position : position // ignore: cast_nullable_to_non_nullable
as String?,photo: freezed == photo ? _self.photo : photo // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}

}


/// Adds pattern-matching-related methods to [SpeakerBrief].
extension SpeakerBriefPatterns on SpeakerBrief {
/// A variant of `map` that fallback to returning `orElse`.
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case final Subclass value:
///     return ...;
///   case _:
///     return orElse();
/// }
/// ```

@optionalTypeArgs TResult maybeMap<TResult extends Object?>(TResult Function( _SpeakerBrief value)?  $default,{required TResult orElse(),}){
final _that = this;
switch (_that) {
case _SpeakerBrief() when $default != null:
return $default(_that);case _:
  return orElse();

}
}
/// A `switch`-like method, using callbacks.
///
/// Callbacks receives the raw object, upcasted.
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case final Subclass value:
///     return ...;
///   case final Subclass2 value:
///     return ...;
/// }
/// ```

@optionalTypeArgs TResult map<TResult extends Object?>(TResult Function( _SpeakerBrief value)  $default,){
final _that = this;
switch (_that) {
case _SpeakerBrief():
return $default(_that);case _:
  throw StateError('Unexpected subclass');

}
}
/// A variant of `map` that fallback to returning `null`.
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case final Subclass value:
///     return ...;
///   case _:
///     return null;
/// }
/// ```

@optionalTypeArgs TResult? mapOrNull<TResult extends Object?>(TResult? Function( _SpeakerBrief value)?  $default,){
final _that = this;
switch (_that) {
case _SpeakerBrief() when $default != null:
return $default(_that);case _:
  return null;

}
}
/// A variant of `when` that fallback to an `orElse` callback.
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case Subclass(:final field):
///     return ...;
///   case _:
///     return orElse();
/// }
/// ```

@optionalTypeArgs TResult maybeWhen<TResult extends Object?>(TResult Function( int id,  String name,  String? position,  String? photo)?  $default,{required TResult orElse(),}) {final _that = this;
switch (_that) {
case _SpeakerBrief() when $default != null:
return $default(_that.id,_that.name,_that.position,_that.photo);case _:
  return orElse();

}
}
/// A `switch`-like method, using callbacks.
///
/// As opposed to `map`, this offers destructuring.
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case Subclass(:final field):
///     return ...;
///   case Subclass2(:final field2):
///     return ...;
/// }
/// ```

@optionalTypeArgs TResult when<TResult extends Object?>(TResult Function( int id,  String name,  String? position,  String? photo)  $default,) {final _that = this;
switch (_that) {
case _SpeakerBrief():
return $default(_that.id,_that.name,_that.position,_that.photo);case _:
  throw StateError('Unexpected subclass');

}
}
/// A variant of `when` that fallback to returning `null`
///
/// It is equivalent to doing:
/// ```dart
/// switch (sealedClass) {
///   case Subclass(:final field):
///     return ...;
///   case _:
///     return null;
/// }
/// ```

@optionalTypeArgs TResult? whenOrNull<TResult extends Object?>(TResult? Function( int id,  String name,  String? position,  String? photo)?  $default,) {final _that = this;
switch (_that) {
case _SpeakerBrief() when $default != null:
return $default(_that.id,_that.name,_that.position,_that.photo);case _:
  return null;

}
}

}

/// @nodoc
@JsonSerializable()

class _SpeakerBrief implements SpeakerBrief {
  const _SpeakerBrief({required this.id, required this.name, this.position, this.photo});
  factory _SpeakerBrief.fromJson(Map<String, dynamic> json) => _$SpeakerBriefFromJson(json);

@override final  int id;
@override final  String name;
@override final  String? position;
@override final  String? photo;

/// Create a copy of SpeakerBrief
/// with the given fields replaced by the non-null parameter values.
@override @JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
_$SpeakerBriefCopyWith<_SpeakerBrief> get copyWith => __$SpeakerBriefCopyWithImpl<_SpeakerBrief>(this, _$identity);

@override
Map<String, dynamic> toJson() {
  return _$SpeakerBriefToJson(this, );
}

@override
bool operator ==(Object other) {
    return identical(this, other) || (other.runtimeType == runtimeType&&other is _SpeakerBrief&&(identical(other.id, id) || other.id == id)&&(identical(other.name, name) || other.name == name)&&(identical(other.position, position) || other.position == position)&&(identical(other.photo, photo) || other.photo == photo));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
    return Object.hash(runtimeType,id,name,position,photo);
}

@override
String toString() {
    return 'SpeakerBrief(id: $id, name: $name, position: $position, photo: $photo)';
}


}

/// @nodoc
abstract mixin class _$SpeakerBriefCopyWith<$Res> implements $SpeakerBriefCopyWith<$Res> {
  factory _$SpeakerBriefCopyWith(_SpeakerBrief value, $Res Function(_SpeakerBrief) _then) = __$SpeakerBriefCopyWithImpl;
@override @useResult
$Res call({
 int id, String name, String? position, String? photo
});




}
/// @nodoc
class __$SpeakerBriefCopyWithImpl<$Res>
    implements _$SpeakerBriefCopyWith<$Res> {
  __$SpeakerBriefCopyWithImpl(this._self, this._then);

  final _SpeakerBrief _self;
  final $Res Function(_SpeakerBrief) _then;

/// Create a copy of SpeakerBrief
/// with the given fields replaced by the non-null parameter values.
@override @pragma('vm:prefer-inline') $Res call({Object? id = null,Object? name = null,Object? position = freezed,Object? photo = freezed,}) {
  return _then(_SpeakerBrief(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,name: null == name ? _self.name : name // ignore: cast_nullable_to_non_nullable
as String,position: freezed == position ? _self.position : position // ignore: cast_nullable_to_non_nullable
as String?,photo: freezed == photo ? _self.photo : photo // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}


}

// dart format on
