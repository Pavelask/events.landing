// GENERATED CODE - DO NOT MODIFY BY HAND
// coverage:ignore-file
// ignore_for_file: type=lint, type=warning, deprecated_member_use, deprecated_member_use_from_same_package
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'ticket.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

// GENERATED CODE - DO NOT MODIFY BY HAND
// dart format off
T _$identity<T>(T value) => value;

/// @nodoc
mixin _$ParticipantInfo {

 int get id; String? get name; String? get email;
/// Create a copy of ParticipantInfo
/// with the given fields replaced by the non-null parameter values.
@JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
$ParticipantInfoCopyWith<ParticipantInfo> get copyWith => _$ParticipantInfoCopyWithImpl<ParticipantInfo>(this as ParticipantInfo, _$identity);

  /// Serializes this ParticipantInfo to a JSON map.
  Map<String, dynamic> toJson();


@override
bool operator ==(Object other) {
  final _this = this as ParticipantInfo;
  return identical(this, other) || (other.runtimeType == runtimeType&&other is ParticipantInfo&&(identical(other.id, _this.id) || other.id == _this.id)&&(identical(other.name, _this.name) || other.name == _this.name)&&(identical(other.email, _this.email) || other.email == _this.email));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
  final _this = this as ParticipantInfo;
  return Object.hash(runtimeType,_this.id,_this.name,_this.email);
}

@override
String toString() {
  final _this = this as ParticipantInfo;
  return 'ParticipantInfo(id: ${_this.id}, name: ${_this.name}, email: ${_this.email})';
}


}

/// @nodoc
abstract mixin class $ParticipantInfoCopyWith<$Res>  {
  factory $ParticipantInfoCopyWith(ParticipantInfo value, $Res Function(ParticipantInfo) _then) = _$ParticipantInfoCopyWithImpl;
@useResult
$Res call({
 int id, String? name, String? email
});




}
/// @nodoc
class _$ParticipantInfoCopyWithImpl<$Res>
    implements $ParticipantInfoCopyWith<$Res> {
  _$ParticipantInfoCopyWithImpl(this._self, this._then);

  final ParticipantInfo _self;
  final $Res Function(ParticipantInfo) _then;

/// Create a copy of ParticipantInfo
/// with the given fields replaced by the non-null parameter values.
@pragma('vm:prefer-inline') @override $Res call({Object? id = null,Object? name = freezed,Object? email = freezed,}) {
  return _then(ParticipantInfo(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,name: freezed == name ? _self.name : name // ignore: cast_nullable_to_non_nullable
as String?,email: freezed == email ? _self.email : email // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}

}


/// Adds pattern-matching-related methods to [ParticipantInfo].
extension ParticipantInfoPatterns on ParticipantInfo {
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

@optionalTypeArgs TResult maybeMap<TResult extends Object?>(TResult Function( _ParticipantInfo value)?  $default,{required TResult orElse(),}){
final _that = this;
switch (_that) {
case _ParticipantInfo() when $default != null:
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

@optionalTypeArgs TResult map<TResult extends Object?>(TResult Function( _ParticipantInfo value)  $default,){
final _that = this;
switch (_that) {
case _ParticipantInfo():
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

@optionalTypeArgs TResult? mapOrNull<TResult extends Object?>(TResult? Function( _ParticipantInfo value)?  $default,){
final _that = this;
switch (_that) {
case _ParticipantInfo() when $default != null:
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

@optionalTypeArgs TResult maybeWhen<TResult extends Object?>(TResult Function( int id,  String? name,  String? email)?  $default,{required TResult orElse(),}) {final _that = this;
switch (_that) {
case _ParticipantInfo() when $default != null:
return $default(_that.id,_that.name,_that.email);case _:
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

@optionalTypeArgs TResult when<TResult extends Object?>(TResult Function( int id,  String? name,  String? email)  $default,) {final _that = this;
switch (_that) {
case _ParticipantInfo():
return $default(_that.id,_that.name,_that.email);case _:
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

@optionalTypeArgs TResult? whenOrNull<TResult extends Object?>(TResult? Function( int id,  String? name,  String? email)?  $default,) {final _that = this;
switch (_that) {
case _ParticipantInfo() when $default != null:
return $default(_that.id,_that.name,_that.email);case _:
  return null;

}
}

}

/// @nodoc
@JsonSerializable()

class _ParticipantInfo implements ParticipantInfo {
  const _ParticipantInfo({required this.id, this.name, this.email});
  factory _ParticipantInfo.fromJson(Map<String, dynamic> json) => _$ParticipantInfoFromJson(json);

@override final  int id;
@override final  String? name;
@override final  String? email;

/// Create a copy of ParticipantInfo
/// with the given fields replaced by the non-null parameter values.
@override @JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
_$ParticipantInfoCopyWith<_ParticipantInfo> get copyWith => __$ParticipantInfoCopyWithImpl<_ParticipantInfo>(this, _$identity);

@override
Map<String, dynamic> toJson() {
  return _$ParticipantInfoToJson(this, );
}

@override
bool operator ==(Object other) {
    return identical(this, other) || (other.runtimeType == runtimeType&&other is _ParticipantInfo&&(identical(other.id, id) || other.id == id)&&(identical(other.name, name) || other.name == name)&&(identical(other.email, email) || other.email == email));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
    return Object.hash(runtimeType,id,name,email);
}

@override
String toString() {
    return 'ParticipantInfo(id: $id, name: $name, email: $email)';
}


}

/// @nodoc
abstract mixin class _$ParticipantInfoCopyWith<$Res> implements $ParticipantInfoCopyWith<$Res> {
  factory _$ParticipantInfoCopyWith(_ParticipantInfo value, $Res Function(_ParticipantInfo) _then) = __$ParticipantInfoCopyWithImpl;
@override @useResult
$Res call({
 int id, String? name, String? email
});




}
/// @nodoc
class __$ParticipantInfoCopyWithImpl<$Res>
    implements _$ParticipantInfoCopyWith<$Res> {
  __$ParticipantInfoCopyWithImpl(this._self, this._then);

  final _ParticipantInfo _self;
  final $Res Function(_ParticipantInfo) _then;

/// Create a copy of ParticipantInfo
/// with the given fields replaced by the non-null parameter values.
@override @pragma('vm:prefer-inline') $Res call({Object? id = null,Object? name = freezed,Object? email = freezed,}) {
  return _then(_ParticipantInfo(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,name: freezed == name ? _self.name : name // ignore: cast_nullable_to_non_nullable
as String?,email: freezed == email ? _self.email : email // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}


}


/// @nodoc
mixin _$TicketEvent {

 String? get slug; String? get title; DateTime? get startDate; DateTime? get endDate; String? get venueName; String? get venueAddress; String? get posterImage;
/// Create a copy of TicketEvent
/// with the given fields replaced by the non-null parameter values.
@JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
$TicketEventCopyWith<TicketEvent> get copyWith => _$TicketEventCopyWithImpl<TicketEvent>(this as TicketEvent, _$identity);

  /// Serializes this TicketEvent to a JSON map.
  Map<String, dynamic> toJson();


@override
bool operator ==(Object other) {
  final _this = this as TicketEvent;
  return identical(this, other) || (other.runtimeType == runtimeType&&other is TicketEvent&&(identical(other.slug, _this.slug) || other.slug == _this.slug)&&(identical(other.title, _this.title) || other.title == _this.title)&&(identical(other.startDate, _this.startDate) || other.startDate == _this.startDate)&&(identical(other.endDate, _this.endDate) || other.endDate == _this.endDate)&&(identical(other.venueName, _this.venueName) || other.venueName == _this.venueName)&&(identical(other.venueAddress, _this.venueAddress) || other.venueAddress == _this.venueAddress)&&(identical(other.posterImage, _this.posterImage) || other.posterImage == _this.posterImage));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
  final _this = this as TicketEvent;
  return Object.hash(runtimeType,_this.slug,_this.title,_this.startDate,_this.endDate,_this.venueName,_this.venueAddress,_this.posterImage);
}

@override
String toString() {
  final _this = this as TicketEvent;
  return 'TicketEvent(slug: ${_this.slug}, title: ${_this.title}, startDate: ${_this.startDate}, endDate: ${_this.endDate}, venueName: ${_this.venueName}, venueAddress: ${_this.venueAddress}, posterImage: ${_this.posterImage})';
}


}

/// @nodoc
abstract mixin class $TicketEventCopyWith<$Res>  {
  factory $TicketEventCopyWith(TicketEvent value, $Res Function(TicketEvent) _then) = _$TicketEventCopyWithImpl;
@useResult
$Res call({
 String? slug, String? title, DateTime? startDate, DateTime? endDate, String? venueName, String? venueAddress, String? posterImage
});




}
/// @nodoc
class _$TicketEventCopyWithImpl<$Res>
    implements $TicketEventCopyWith<$Res> {
  _$TicketEventCopyWithImpl(this._self, this._then);

  final TicketEvent _self;
  final $Res Function(TicketEvent) _then;

/// Create a copy of TicketEvent
/// with the given fields replaced by the non-null parameter values.
@pragma('vm:prefer-inline') @override $Res call({Object? slug = freezed,Object? title = freezed,Object? startDate = freezed,Object? endDate = freezed,Object? venueName = freezed,Object? venueAddress = freezed,Object? posterImage = freezed,}) {
  return _then(TicketEvent(
slug: freezed == slug ? _self.slug : slug // ignore: cast_nullable_to_non_nullable
as String?,title: freezed == title ? _self.title : title // ignore: cast_nullable_to_non_nullable
as String?,startDate: freezed == startDate ? _self.startDate : startDate // ignore: cast_nullable_to_non_nullable
as DateTime?,endDate: freezed == endDate ? _self.endDate : endDate // ignore: cast_nullable_to_non_nullable
as DateTime?,venueName: freezed == venueName ? _self.venueName : venueName // ignore: cast_nullable_to_non_nullable
as String?,venueAddress: freezed == venueAddress ? _self.venueAddress : venueAddress // ignore: cast_nullable_to_non_nullable
as String?,posterImage: freezed == posterImage ? _self.posterImage : posterImage // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}

}


/// Adds pattern-matching-related methods to [TicketEvent].
extension TicketEventPatterns on TicketEvent {
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

@optionalTypeArgs TResult maybeMap<TResult extends Object?>(TResult Function( _TicketEvent value)?  $default,{required TResult orElse(),}){
final _that = this;
switch (_that) {
case _TicketEvent() when $default != null:
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

@optionalTypeArgs TResult map<TResult extends Object?>(TResult Function( _TicketEvent value)  $default,){
final _that = this;
switch (_that) {
case _TicketEvent():
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

@optionalTypeArgs TResult? mapOrNull<TResult extends Object?>(TResult? Function( _TicketEvent value)?  $default,){
final _that = this;
switch (_that) {
case _TicketEvent() when $default != null:
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

@optionalTypeArgs TResult maybeWhen<TResult extends Object?>(TResult Function( String? slug,  String? title,  DateTime? startDate,  DateTime? endDate,  String? venueName,  String? venueAddress,  String? posterImage)?  $default,{required TResult orElse(),}) {final _that = this;
switch (_that) {
case _TicketEvent() when $default != null:
return $default(_that.slug,_that.title,_that.startDate,_that.endDate,_that.venueName,_that.venueAddress,_that.posterImage);case _:
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

@optionalTypeArgs TResult when<TResult extends Object?>(TResult Function( String? slug,  String? title,  DateTime? startDate,  DateTime? endDate,  String? venueName,  String? venueAddress,  String? posterImage)  $default,) {final _that = this;
switch (_that) {
case _TicketEvent():
return $default(_that.slug,_that.title,_that.startDate,_that.endDate,_that.venueName,_that.venueAddress,_that.posterImage);case _:
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

@optionalTypeArgs TResult? whenOrNull<TResult extends Object?>(TResult? Function( String? slug,  String? title,  DateTime? startDate,  DateTime? endDate,  String? venueName,  String? venueAddress,  String? posterImage)?  $default,) {final _that = this;
switch (_that) {
case _TicketEvent() when $default != null:
return $default(_that.slug,_that.title,_that.startDate,_that.endDate,_that.venueName,_that.venueAddress,_that.posterImage);case _:
  return null;

}
}

}

/// @nodoc
@JsonSerializable()

class _TicketEvent implements TicketEvent {
  const _TicketEvent({this.slug, this.title, this.startDate, this.endDate, this.venueName, this.venueAddress, this.posterImage});
  factory _TicketEvent.fromJson(Map<String, dynamic> json) => _$TicketEventFromJson(json);

@override final  String? slug;
@override final  String? title;
@override final  DateTime? startDate;
@override final  DateTime? endDate;
@override final  String? venueName;
@override final  String? venueAddress;
@override final  String? posterImage;

/// Create a copy of TicketEvent
/// with the given fields replaced by the non-null parameter values.
@override @JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
_$TicketEventCopyWith<_TicketEvent> get copyWith => __$TicketEventCopyWithImpl<_TicketEvent>(this, _$identity);

@override
Map<String, dynamic> toJson() {
  return _$TicketEventToJson(this, );
}

@override
bool operator ==(Object other) {
    return identical(this, other) || (other.runtimeType == runtimeType&&other is _TicketEvent&&(identical(other.slug, slug) || other.slug == slug)&&(identical(other.title, title) || other.title == title)&&(identical(other.startDate, startDate) || other.startDate == startDate)&&(identical(other.endDate, endDate) || other.endDate == endDate)&&(identical(other.venueName, venueName) || other.venueName == venueName)&&(identical(other.venueAddress, venueAddress) || other.venueAddress == venueAddress)&&(identical(other.posterImage, posterImage) || other.posterImage == posterImage));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
    return Object.hash(runtimeType,slug,title,startDate,endDate,venueName,venueAddress,posterImage);
}

@override
String toString() {
    return 'TicketEvent(slug: $slug, title: $title, startDate: $startDate, endDate: $endDate, venueName: $venueName, venueAddress: $venueAddress, posterImage: $posterImage)';
}


}

/// @nodoc
abstract mixin class _$TicketEventCopyWith<$Res> implements $TicketEventCopyWith<$Res> {
  factory _$TicketEventCopyWith(_TicketEvent value, $Res Function(_TicketEvent) _then) = __$TicketEventCopyWithImpl;
@override @useResult
$Res call({
 String? slug, String? title, DateTime? startDate, DateTime? endDate, String? venueName, String? venueAddress, String? posterImage
});




}
/// @nodoc
class __$TicketEventCopyWithImpl<$Res>
    implements _$TicketEventCopyWith<$Res> {
  __$TicketEventCopyWithImpl(this._self, this._then);

  final _TicketEvent _self;
  final $Res Function(_TicketEvent) _then;

/// Create a copy of TicketEvent
/// with the given fields replaced by the non-null parameter values.
@override @pragma('vm:prefer-inline') $Res call({Object? slug = freezed,Object? title = freezed,Object? startDate = freezed,Object? endDate = freezed,Object? venueName = freezed,Object? venueAddress = freezed,Object? posterImage = freezed,}) {
  return _then(_TicketEvent(
slug: freezed == slug ? _self.slug : slug // ignore: cast_nullable_to_non_nullable
as String?,title: freezed == title ? _self.title : title // ignore: cast_nullable_to_non_nullable
as String?,startDate: freezed == startDate ? _self.startDate : startDate // ignore: cast_nullable_to_non_nullable
as DateTime?,endDate: freezed == endDate ? _self.endDate : endDate // ignore: cast_nullable_to_non_nullable
as DateTime?,venueName: freezed == venueName ? _self.venueName : venueName // ignore: cast_nullable_to_non_nullable
as String?,venueAddress: freezed == venueAddress ? _self.venueAddress : venueAddress // ignore: cast_nullable_to_non_nullable
as String?,posterImage: freezed == posterImage ? _self.posterImage : posterImage // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}


}


/// @nodoc
mixin _$Ticket {

 String get type; String get token; String? get status; bool get isCheckedIn; bool? get alreadyCheckedIn; DateTime? get checkedInAt; ParticipantInfo? get participant; TicketEvent? get event; String? get checkinUrl; String? get qrUrl; String? get ticketUrl;
/// Create a copy of Ticket
/// with the given fields replaced by the non-null parameter values.
@JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
$TicketCopyWith<Ticket> get copyWith => _$TicketCopyWithImpl<Ticket>(this as Ticket, _$identity);

  /// Serializes this Ticket to a JSON map.
  Map<String, dynamic> toJson();


@override
bool operator ==(Object other) {
  final _this = this as Ticket;
  return identical(this, other) || (other.runtimeType == runtimeType&&other is Ticket&&(identical(other.type, _this.type) || other.type == _this.type)&&(identical(other.token, _this.token) || other.token == _this.token)&&(identical(other.status, _this.status) || other.status == _this.status)&&(identical(other.isCheckedIn, _this.isCheckedIn) || other.isCheckedIn == _this.isCheckedIn)&&(identical(other.alreadyCheckedIn, _this.alreadyCheckedIn) || other.alreadyCheckedIn == _this.alreadyCheckedIn)&&(identical(other.checkedInAt, _this.checkedInAt) || other.checkedInAt == _this.checkedInAt)&&(identical(other.participant, _this.participant) || other.participant == _this.participant)&&(identical(other.event, _this.event) || other.event == _this.event)&&(identical(other.checkinUrl, _this.checkinUrl) || other.checkinUrl == _this.checkinUrl)&&(identical(other.qrUrl, _this.qrUrl) || other.qrUrl == _this.qrUrl)&&(identical(other.ticketUrl, _this.ticketUrl) || other.ticketUrl == _this.ticketUrl));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
  final _this = this as Ticket;
  return Object.hash(runtimeType,_this.type,_this.token,_this.status,_this.isCheckedIn,_this.alreadyCheckedIn,_this.checkedInAt,_this.participant,_this.event,_this.checkinUrl,_this.qrUrl,_this.ticketUrl);
}

@override
String toString() {
  final _this = this as Ticket;
  return 'Ticket(type: ${_this.type}, token: ${_this.token}, status: ${_this.status}, isCheckedIn: ${_this.isCheckedIn}, alreadyCheckedIn: ${_this.alreadyCheckedIn}, checkedInAt: ${_this.checkedInAt}, participant: ${_this.participant}, event: ${_this.event}, checkinUrl: ${_this.checkinUrl}, qrUrl: ${_this.qrUrl}, ticketUrl: ${_this.ticketUrl})';
}


}

/// @nodoc
abstract mixin class $TicketCopyWith<$Res>  {
  factory $TicketCopyWith(Ticket value, $Res Function(Ticket) _then) = _$TicketCopyWithImpl;
@useResult
$Res call({
 String type, String token, String? status, bool isCheckedIn, bool? alreadyCheckedIn, DateTime? checkedInAt, ParticipantInfo? participant, TicketEvent? event, String? checkinUrl, String? qrUrl, String? ticketUrl
});


$ParticipantInfoCopyWith<$Res>? get participant;$TicketEventCopyWith<$Res>? get event;

}
/// @nodoc
class _$TicketCopyWithImpl<$Res>
    implements $TicketCopyWith<$Res> {
  _$TicketCopyWithImpl(this._self, this._then);

  final Ticket _self;
  final $Res Function(Ticket) _then;

/// Create a copy of Ticket
/// with the given fields replaced by the non-null parameter values.
@pragma('vm:prefer-inline') @override $Res call({Object? type = null,Object? token = null,Object? status = freezed,Object? isCheckedIn = null,Object? alreadyCheckedIn = freezed,Object? checkedInAt = freezed,Object? participant = freezed,Object? event = freezed,Object? checkinUrl = freezed,Object? qrUrl = freezed,Object? ticketUrl = freezed,}) {
  return _then(Ticket(
type: null == type ? _self.type : type // ignore: cast_nullable_to_non_nullable
as String,token: null == token ? _self.token : token // ignore: cast_nullable_to_non_nullable
as String,status: freezed == status ? _self.status : status // ignore: cast_nullable_to_non_nullable
as String?,isCheckedIn: null == isCheckedIn ? _self.isCheckedIn : isCheckedIn // ignore: cast_nullable_to_non_nullable
as bool,alreadyCheckedIn: freezed == alreadyCheckedIn ? _self.alreadyCheckedIn : alreadyCheckedIn // ignore: cast_nullable_to_non_nullable
as bool?,checkedInAt: freezed == checkedInAt ? _self.checkedInAt : checkedInAt // ignore: cast_nullable_to_non_nullable
as DateTime?,participant: freezed == participant ? _self.participant : participant // ignore: cast_nullable_to_non_nullable
as ParticipantInfo?,event: freezed == event ? _self.event : event // ignore: cast_nullable_to_non_nullable
as TicketEvent?,checkinUrl: freezed == checkinUrl ? _self.checkinUrl : checkinUrl // ignore: cast_nullable_to_non_nullable
as String?,qrUrl: freezed == qrUrl ? _self.qrUrl : qrUrl // ignore: cast_nullable_to_non_nullable
as String?,ticketUrl: freezed == ticketUrl ? _self.ticketUrl : ticketUrl // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}
/// Create a copy of Ticket
/// with the given fields replaced by the non-null parameter values.
@override
@pragma('vm:prefer-inline')
$ParticipantInfoCopyWith<$Res>? get participant {
    if (_self.participant == null) {
    return null;
  }

  return $ParticipantInfoCopyWith<$Res>(_self.participant!, (value) {
    return _then(_self.copyWith(participant: value));
  });
}/// Create a copy of Ticket
/// with the given fields replaced by the non-null parameter values.
@override
@pragma('vm:prefer-inline')
$TicketEventCopyWith<$Res>? get event {
    if (_self.event == null) {
    return null;
  }

  return $TicketEventCopyWith<$Res>(_self.event!, (value) {
    return _then(_self.copyWith(event: value));
  });
}
}


/// Adds pattern-matching-related methods to [Ticket].
extension TicketPatterns on Ticket {
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

@optionalTypeArgs TResult maybeMap<TResult extends Object?>(TResult Function( _Ticket value)?  $default,{required TResult orElse(),}){
final _that = this;
switch (_that) {
case _Ticket() when $default != null:
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

@optionalTypeArgs TResult map<TResult extends Object?>(TResult Function( _Ticket value)  $default,){
final _that = this;
switch (_that) {
case _Ticket():
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

@optionalTypeArgs TResult? mapOrNull<TResult extends Object?>(TResult? Function( _Ticket value)?  $default,){
final _that = this;
switch (_that) {
case _Ticket() when $default != null:
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

@optionalTypeArgs TResult maybeWhen<TResult extends Object?>(TResult Function( String type,  String token,  String? status,  bool isCheckedIn,  bool? alreadyCheckedIn,  DateTime? checkedInAt,  ParticipantInfo? participant,  TicketEvent? event,  String? checkinUrl,  String? qrUrl,  String? ticketUrl)?  $default,{required TResult orElse(),}) {final _that = this;
switch (_that) {
case _Ticket() when $default != null:
return $default(_that.type,_that.token,_that.status,_that.isCheckedIn,_that.alreadyCheckedIn,_that.checkedInAt,_that.participant,_that.event,_that.checkinUrl,_that.qrUrl,_that.ticketUrl);case _:
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

@optionalTypeArgs TResult when<TResult extends Object?>(TResult Function( String type,  String token,  String? status,  bool isCheckedIn,  bool? alreadyCheckedIn,  DateTime? checkedInAt,  ParticipantInfo? participant,  TicketEvent? event,  String? checkinUrl,  String? qrUrl,  String? ticketUrl)  $default,) {final _that = this;
switch (_that) {
case _Ticket():
return $default(_that.type,_that.token,_that.status,_that.isCheckedIn,_that.alreadyCheckedIn,_that.checkedInAt,_that.participant,_that.event,_that.checkinUrl,_that.qrUrl,_that.ticketUrl);case _:
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

@optionalTypeArgs TResult? whenOrNull<TResult extends Object?>(TResult? Function( String type,  String token,  String? status,  bool isCheckedIn,  bool? alreadyCheckedIn,  DateTime? checkedInAt,  ParticipantInfo? participant,  TicketEvent? event,  String? checkinUrl,  String? qrUrl,  String? ticketUrl)?  $default,) {final _that = this;
switch (_that) {
case _Ticket() when $default != null:
return $default(_that.type,_that.token,_that.status,_that.isCheckedIn,_that.alreadyCheckedIn,_that.checkedInAt,_that.participant,_that.event,_that.checkinUrl,_that.qrUrl,_that.ticketUrl);case _:
  return null;

}
}

}

/// @nodoc
@JsonSerializable()

class _Ticket implements Ticket {
  const _Ticket({required this.type, required this.token, this.status, this.isCheckedIn = false, this.alreadyCheckedIn, this.checkedInAt, this.participant, this.event, this.checkinUrl, this.qrUrl, this.ticketUrl});
  factory _Ticket.fromJson(Map<String, dynamic> json) => _$TicketFromJson(json);

@override final  String type;
@override final  String token;
@override final  String? status;
@override@JsonKey() final  bool isCheckedIn;
@override final  bool? alreadyCheckedIn;
@override final  DateTime? checkedInAt;
@override final  ParticipantInfo? participant;
@override final  TicketEvent? event;
@override final  String? checkinUrl;
@override final  String? qrUrl;
@override final  String? ticketUrl;

/// Create a copy of Ticket
/// with the given fields replaced by the non-null parameter values.
@override @JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
_$TicketCopyWith<_Ticket> get copyWith => __$TicketCopyWithImpl<_Ticket>(this, _$identity);

@override
Map<String, dynamic> toJson() {
  return _$TicketToJson(this, );
}

@override
bool operator ==(Object other) {
    return identical(this, other) || (other.runtimeType == runtimeType&&other is _Ticket&&(identical(other.type, type) || other.type == type)&&(identical(other.token, token) || other.token == token)&&(identical(other.status, status) || other.status == status)&&(identical(other.isCheckedIn, isCheckedIn) || other.isCheckedIn == isCheckedIn)&&(identical(other.alreadyCheckedIn, alreadyCheckedIn) || other.alreadyCheckedIn == alreadyCheckedIn)&&(identical(other.checkedInAt, checkedInAt) || other.checkedInAt == checkedInAt)&&(identical(other.participant, participant) || other.participant == participant)&&(identical(other.event, event) || other.event == event)&&(identical(other.checkinUrl, checkinUrl) || other.checkinUrl == checkinUrl)&&(identical(other.qrUrl, qrUrl) || other.qrUrl == qrUrl)&&(identical(other.ticketUrl, ticketUrl) || other.ticketUrl == ticketUrl));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
    return Object.hash(runtimeType,type,token,status,isCheckedIn,alreadyCheckedIn,checkedInAt,participant,event,checkinUrl,qrUrl,ticketUrl);
}

@override
String toString() {
    return 'Ticket(type: $type, token: $token, status: $status, isCheckedIn: $isCheckedIn, alreadyCheckedIn: $alreadyCheckedIn, checkedInAt: $checkedInAt, participant: $participant, event: $event, checkinUrl: $checkinUrl, qrUrl: $qrUrl, ticketUrl: $ticketUrl)';
}


}

/// @nodoc
abstract mixin class _$TicketCopyWith<$Res> implements $TicketCopyWith<$Res> {
  factory _$TicketCopyWith(_Ticket value, $Res Function(_Ticket) _then) = __$TicketCopyWithImpl;
@override @useResult
$Res call({
 String type, String token, String? status, bool isCheckedIn, bool? alreadyCheckedIn, DateTime? checkedInAt, ParticipantInfo? participant, TicketEvent? event, String? checkinUrl, String? qrUrl, String? ticketUrl
});


@override $ParticipantInfoCopyWith<$Res>? get participant;@override $TicketEventCopyWith<$Res>? get event;

}
/// @nodoc
class __$TicketCopyWithImpl<$Res>
    implements _$TicketCopyWith<$Res> {
  __$TicketCopyWithImpl(this._self, this._then);

  final _Ticket _self;
  final $Res Function(_Ticket) _then;

/// Create a copy of Ticket
/// with the given fields replaced by the non-null parameter values.
@override @pragma('vm:prefer-inline') $Res call({Object? type = null,Object? token = null,Object? status = freezed,Object? isCheckedIn = null,Object? alreadyCheckedIn = freezed,Object? checkedInAt = freezed,Object? participant = freezed,Object? event = freezed,Object? checkinUrl = freezed,Object? qrUrl = freezed,Object? ticketUrl = freezed,}) {
  return _then(_Ticket(
type: null == type ? _self.type : type // ignore: cast_nullable_to_non_nullable
as String,token: null == token ? _self.token : token // ignore: cast_nullable_to_non_nullable
as String,status: freezed == status ? _self.status : status // ignore: cast_nullable_to_non_nullable
as String?,isCheckedIn: null == isCheckedIn ? _self.isCheckedIn : isCheckedIn // ignore: cast_nullable_to_non_nullable
as bool,alreadyCheckedIn: freezed == alreadyCheckedIn ? _self.alreadyCheckedIn : alreadyCheckedIn // ignore: cast_nullable_to_non_nullable
as bool?,checkedInAt: freezed == checkedInAt ? _self.checkedInAt : checkedInAt // ignore: cast_nullable_to_non_nullable
as DateTime?,participant: freezed == participant ? _self.participant : participant // ignore: cast_nullable_to_non_nullable
as ParticipantInfo?,event: freezed == event ? _self.event : event // ignore: cast_nullable_to_non_nullable
as TicketEvent?,checkinUrl: freezed == checkinUrl ? _self.checkinUrl : checkinUrl // ignore: cast_nullable_to_non_nullable
as String?,qrUrl: freezed == qrUrl ? _self.qrUrl : qrUrl // ignore: cast_nullable_to_non_nullable
as String?,ticketUrl: freezed == ticketUrl ? _self.ticketUrl : ticketUrl // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}

/// Create a copy of Ticket
/// with the given fields replaced by the non-null parameter values.
@override
@pragma('vm:prefer-inline')
$ParticipantInfoCopyWith<$Res>? get participant {
    if (_self.participant == null) {
    return null;
  }

  return $ParticipantInfoCopyWith<$Res>(_self.participant!, (value) {
    return _then(_self.copyWith(participant: value));
  });
}/// Create a copy of Ticket
/// with the given fields replaced by the non-null parameter values.
@override
@pragma('vm:prefer-inline')
$TicketEventCopyWith<$Res>? get event {
    if (_self.event == null) {
    return null;
  }

  return $TicketEventCopyWith<$Res>(_self.event!, (value) {
    return _then(_self.copyWith(event: value));
  });
}
}

// dart format on
