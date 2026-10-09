// GENERATED CODE - DO NOT MODIFY BY HAND
// coverage:ignore-file
// ignore_for_file: type=lint, type=warning, deprecated_member_use, deprecated_member_use_from_same_package
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'event.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

// GENERATED CODE - DO NOT MODIFY BY HAND
// dart format off
T _$identity<T>(T value) => value;

/// @nodoc
mixin _$Event {

 int get id; String get slug; String get title; String? get description; String get status; DateTime? get startDate; DateTime? get endDate; String? get dailyStartTime; String? get dailyEndTime; String? get venueName; String? get venueAddress; String? get posterImage; String? get logo; String? get videoUrl; bool get isRegistrationOpen; String? get registrationType; String? get registrationUrl; String? get yandexFormId; String? get mediaImage; String? get mediaDescription; bool get isMediaVisible; List<String> get gallery; String? get galleryExternalUrl; String? get galleryExternalDescription; bool get isGalleryExternalVisible; List<EventDay> get days; List<Speaker> get speakers; List<Guest> get guests; List<Testimonial> get testimonials; List<Faq> get faqs; List<EventDocument> get documents;
/// Create a copy of Event
/// with the given fields replaced by the non-null parameter values.
@JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
$EventCopyWith<Event> get copyWith => _$EventCopyWithImpl<Event>(this as Event, _$identity);

  /// Serializes this Event to a JSON map.
  Map<String, dynamic> toJson();


@override
bool operator ==(Object other) {
  final _this = this as Event;
  return identical(this, other) || (other.runtimeType == runtimeType&&other is Event&&(identical(other.id, _this.id) || other.id == _this.id)&&(identical(other.slug, _this.slug) || other.slug == _this.slug)&&(identical(other.title, _this.title) || other.title == _this.title)&&(identical(other.description, _this.description) || other.description == _this.description)&&(identical(other.status, _this.status) || other.status == _this.status)&&(identical(other.startDate, _this.startDate) || other.startDate == _this.startDate)&&(identical(other.endDate, _this.endDate) || other.endDate == _this.endDate)&&(identical(other.dailyStartTime, _this.dailyStartTime) || other.dailyStartTime == _this.dailyStartTime)&&(identical(other.dailyEndTime, _this.dailyEndTime) || other.dailyEndTime == _this.dailyEndTime)&&(identical(other.venueName, _this.venueName) || other.venueName == _this.venueName)&&(identical(other.venueAddress, _this.venueAddress) || other.venueAddress == _this.venueAddress)&&(identical(other.posterImage, _this.posterImage) || other.posterImage == _this.posterImage)&&(identical(other.logo, _this.logo) || other.logo == _this.logo)&&(identical(other.videoUrl, _this.videoUrl) || other.videoUrl == _this.videoUrl)&&(identical(other.isRegistrationOpen, _this.isRegistrationOpen) || other.isRegistrationOpen == _this.isRegistrationOpen)&&(identical(other.registrationType, _this.registrationType) || other.registrationType == _this.registrationType)&&(identical(other.registrationUrl, _this.registrationUrl) || other.registrationUrl == _this.registrationUrl)&&(identical(other.yandexFormId, _this.yandexFormId) || other.yandexFormId == _this.yandexFormId)&&(identical(other.mediaImage, _this.mediaImage) || other.mediaImage == _this.mediaImage)&&(identical(other.mediaDescription, _this.mediaDescription) || other.mediaDescription == _this.mediaDescription)&&(identical(other.isMediaVisible, _this.isMediaVisible) || other.isMediaVisible == _this.isMediaVisible)&&const DeepCollectionEquality().equals(other.gallery, _this.gallery)&&(identical(other.galleryExternalUrl, _this.galleryExternalUrl) || other.galleryExternalUrl == _this.galleryExternalUrl)&&(identical(other.galleryExternalDescription, _this.galleryExternalDescription) || other.galleryExternalDescription == _this.galleryExternalDescription)&&(identical(other.isGalleryExternalVisible, _this.isGalleryExternalVisible) || other.isGalleryExternalVisible == _this.isGalleryExternalVisible)&&const DeepCollectionEquality().equals(other.days, _this.days)&&const DeepCollectionEquality().equals(other.speakers, _this.speakers)&&const DeepCollectionEquality().equals(other.guests, _this.guests)&&const DeepCollectionEquality().equals(other.testimonials, _this.testimonials)&&const DeepCollectionEquality().equals(other.faqs, _this.faqs)&&const DeepCollectionEquality().equals(other.documents, _this.documents));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
  final _this = this as Event;
  return Object.hashAll([runtimeType,_this.id,_this.slug,_this.title,_this.description,_this.status,_this.startDate,_this.endDate,_this.dailyStartTime,_this.dailyEndTime,_this.venueName,_this.venueAddress,_this.posterImage,_this.logo,_this.videoUrl,_this.isRegistrationOpen,_this.registrationType,_this.registrationUrl,_this.yandexFormId,_this.mediaImage,_this.mediaDescription,_this.isMediaVisible,const DeepCollectionEquality().hash(_this.gallery),_this.galleryExternalUrl,_this.galleryExternalDescription,_this.isGalleryExternalVisible,const DeepCollectionEquality().hash(_this.days),const DeepCollectionEquality().hash(_this.speakers),const DeepCollectionEquality().hash(_this.guests),const DeepCollectionEquality().hash(_this.testimonials),const DeepCollectionEquality().hash(_this.faqs),const DeepCollectionEquality().hash(_this.documents)]);
}

@override
String toString() {
  final _this = this as Event;
  return 'Event(id: ${_this.id}, slug: ${_this.slug}, title: ${_this.title}, description: ${_this.description}, status: ${_this.status}, startDate: ${_this.startDate}, endDate: ${_this.endDate}, dailyStartTime: ${_this.dailyStartTime}, dailyEndTime: ${_this.dailyEndTime}, venueName: ${_this.venueName}, venueAddress: ${_this.venueAddress}, posterImage: ${_this.posterImage}, logo: ${_this.logo}, videoUrl: ${_this.videoUrl}, isRegistrationOpen: ${_this.isRegistrationOpen}, registrationType: ${_this.registrationType}, registrationUrl: ${_this.registrationUrl}, yandexFormId: ${_this.yandexFormId}, mediaImage: ${_this.mediaImage}, mediaDescription: ${_this.mediaDescription}, isMediaVisible: ${_this.isMediaVisible}, gallery: ${_this.gallery}, galleryExternalUrl: ${_this.galleryExternalUrl}, galleryExternalDescription: ${_this.galleryExternalDescription}, isGalleryExternalVisible: ${_this.isGalleryExternalVisible}, days: ${_this.days}, speakers: ${_this.speakers}, guests: ${_this.guests}, testimonials: ${_this.testimonials}, faqs: ${_this.faqs}, documents: ${_this.documents})';
}


}

/// @nodoc
abstract mixin class $EventCopyWith<$Res>  {
  factory $EventCopyWith(Event value, $Res Function(Event) _then) = _$EventCopyWithImpl;
@useResult
$Res call({
 int id, String slug, String title, String? description, String status, DateTime? startDate, DateTime? endDate, String? dailyStartTime, String? dailyEndTime, String? venueName, String? venueAddress, String? posterImage, String? logo, String? videoUrl, bool isRegistrationOpen, String? registrationType, String? registrationUrl, String? yandexFormId, String? mediaImage, String? mediaDescription, bool isMediaVisible, List<String> gallery, String? galleryExternalUrl, String? galleryExternalDescription, bool isGalleryExternalVisible, List<EventDay> days, List<Speaker> speakers, List<Guest> guests, List<Testimonial> testimonials, List<Faq> faqs, List<EventDocument> documents
});




}
/// @nodoc
class _$EventCopyWithImpl<$Res>
    implements $EventCopyWith<$Res> {
  _$EventCopyWithImpl(this._self, this._then);

  final Event _self;
  final $Res Function(Event) _then;

/// Create a copy of Event
/// with the given fields replaced by the non-null parameter values.
@pragma('vm:prefer-inline') @override $Res call({Object? id = null,Object? slug = null,Object? title = null,Object? description = freezed,Object? status = null,Object? startDate = freezed,Object? endDate = freezed,Object? dailyStartTime = freezed,Object? dailyEndTime = freezed,Object? venueName = freezed,Object? venueAddress = freezed,Object? posterImage = freezed,Object? logo = freezed,Object? videoUrl = freezed,Object? isRegistrationOpen = null,Object? registrationType = freezed,Object? registrationUrl = freezed,Object? yandexFormId = freezed,Object? mediaImage = freezed,Object? mediaDescription = freezed,Object? isMediaVisible = null,Object? gallery = null,Object? galleryExternalUrl = freezed,Object? galleryExternalDescription = freezed,Object? isGalleryExternalVisible = null,Object? days = null,Object? speakers = null,Object? guests = null,Object? testimonials = null,Object? faqs = null,Object? documents = null,}) {
  return _then(Event(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,slug: null == slug ? _self.slug : slug // ignore: cast_nullable_to_non_nullable
as String,title: null == title ? _self.title : title // ignore: cast_nullable_to_non_nullable
as String,description: freezed == description ? _self.description : description // ignore: cast_nullable_to_non_nullable
as String?,status: null == status ? _self.status : status // ignore: cast_nullable_to_non_nullable
as String,startDate: freezed == startDate ? _self.startDate : startDate // ignore: cast_nullable_to_non_nullable
as DateTime?,endDate: freezed == endDate ? _self.endDate : endDate // ignore: cast_nullable_to_non_nullable
as DateTime?,dailyStartTime: freezed == dailyStartTime ? _self.dailyStartTime : dailyStartTime // ignore: cast_nullable_to_non_nullable
as String?,dailyEndTime: freezed == dailyEndTime ? _self.dailyEndTime : dailyEndTime // ignore: cast_nullable_to_non_nullable
as String?,venueName: freezed == venueName ? _self.venueName : venueName // ignore: cast_nullable_to_non_nullable
as String?,venueAddress: freezed == venueAddress ? _self.venueAddress : venueAddress // ignore: cast_nullable_to_non_nullable
as String?,posterImage: freezed == posterImage ? _self.posterImage : posterImage // ignore: cast_nullable_to_non_nullable
as String?,logo: freezed == logo ? _self.logo : logo // ignore: cast_nullable_to_non_nullable
as String?,videoUrl: freezed == videoUrl ? _self.videoUrl : videoUrl // ignore: cast_nullable_to_non_nullable
as String?,isRegistrationOpen: null == isRegistrationOpen ? _self.isRegistrationOpen : isRegistrationOpen // ignore: cast_nullable_to_non_nullable
as bool,registrationType: freezed == registrationType ? _self.registrationType : registrationType // ignore: cast_nullable_to_non_nullable
as String?,registrationUrl: freezed == registrationUrl ? _self.registrationUrl : registrationUrl // ignore: cast_nullable_to_non_nullable
as String?,yandexFormId: freezed == yandexFormId ? _self.yandexFormId : yandexFormId // ignore: cast_nullable_to_non_nullable
as String?,mediaImage: freezed == mediaImage ? _self.mediaImage : mediaImage // ignore: cast_nullable_to_non_nullable
as String?,mediaDescription: freezed == mediaDescription ? _self.mediaDescription : mediaDescription // ignore: cast_nullable_to_non_nullable
as String?,isMediaVisible: null == isMediaVisible ? _self.isMediaVisible : isMediaVisible // ignore: cast_nullable_to_non_nullable
as bool,gallery: null == gallery ? _self.gallery : gallery // ignore: cast_nullable_to_non_nullable
as List<String>,galleryExternalUrl: freezed == galleryExternalUrl ? _self.galleryExternalUrl : galleryExternalUrl // ignore: cast_nullable_to_non_nullable
as String?,galleryExternalDescription: freezed == galleryExternalDescription ? _self.galleryExternalDescription : galleryExternalDescription // ignore: cast_nullable_to_non_nullable
as String?,isGalleryExternalVisible: null == isGalleryExternalVisible ? _self.isGalleryExternalVisible : isGalleryExternalVisible // ignore: cast_nullable_to_non_nullable
as bool,days: null == days ? _self.days : days // ignore: cast_nullable_to_non_nullable
as List<EventDay>,speakers: null == speakers ? _self.speakers : speakers // ignore: cast_nullable_to_non_nullable
as List<Speaker>,guests: null == guests ? _self.guests : guests // ignore: cast_nullable_to_non_nullable
as List<Guest>,testimonials: null == testimonials ? _self.testimonials : testimonials // ignore: cast_nullable_to_non_nullable
as List<Testimonial>,faqs: null == faqs ? _self.faqs : faqs // ignore: cast_nullable_to_non_nullable
as List<Faq>,documents: null == documents ? _self.documents : documents // ignore: cast_nullable_to_non_nullable
as List<EventDocument>,
  ));
}

}


/// Adds pattern-matching-related methods to [Event].
extension EventPatterns on Event {
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

@optionalTypeArgs TResult maybeMap<TResult extends Object?>(TResult Function( _Event value)?  $default,{required TResult orElse(),}){
final _that = this;
switch (_that) {
case _Event() when $default != null:
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

@optionalTypeArgs TResult map<TResult extends Object?>(TResult Function( _Event value)  $default,){
final _that = this;
switch (_that) {
case _Event():
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

@optionalTypeArgs TResult? mapOrNull<TResult extends Object?>(TResult? Function( _Event value)?  $default,){
final _that = this;
switch (_that) {
case _Event() when $default != null:
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

@optionalTypeArgs TResult maybeWhen<TResult extends Object?>(TResult Function( int id,  String slug,  String title,  String? description,  String status,  DateTime? startDate,  DateTime? endDate,  String? dailyStartTime,  String? dailyEndTime,  String? venueName,  String? venueAddress,  String? posterImage,  String? logo,  String? videoUrl,  bool isRegistrationOpen,  String? registrationType,  String? registrationUrl,  String? yandexFormId,  String? mediaImage,  String? mediaDescription,  bool isMediaVisible,  List<String> gallery,  String? galleryExternalUrl,  String? galleryExternalDescription,  bool isGalleryExternalVisible,  List<EventDay> days,  List<Speaker> speakers,  List<Guest> guests,  List<Testimonial> testimonials,  List<Faq> faqs,  List<EventDocument> documents)?  $default,{required TResult orElse(),}) {final _that = this;
switch (_that) {
case _Event() when $default != null:
return $default(_that.id,_that.slug,_that.title,_that.description,_that.status,_that.startDate,_that.endDate,_that.dailyStartTime,_that.dailyEndTime,_that.venueName,_that.venueAddress,_that.posterImage,_that.logo,_that.videoUrl,_that.isRegistrationOpen,_that.registrationType,_that.registrationUrl,_that.yandexFormId,_that.mediaImage,_that.mediaDescription,_that.isMediaVisible,_that.gallery,_that.galleryExternalUrl,_that.galleryExternalDescription,_that.isGalleryExternalVisible,_that.days,_that.speakers,_that.guests,_that.testimonials,_that.faqs,_that.documents);case _:
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

@optionalTypeArgs TResult when<TResult extends Object?>(TResult Function( int id,  String slug,  String title,  String? description,  String status,  DateTime? startDate,  DateTime? endDate,  String? dailyStartTime,  String? dailyEndTime,  String? venueName,  String? venueAddress,  String? posterImage,  String? logo,  String? videoUrl,  bool isRegistrationOpen,  String? registrationType,  String? registrationUrl,  String? yandexFormId,  String? mediaImage,  String? mediaDescription,  bool isMediaVisible,  List<String> gallery,  String? galleryExternalUrl,  String? galleryExternalDescription,  bool isGalleryExternalVisible,  List<EventDay> days,  List<Speaker> speakers,  List<Guest> guests,  List<Testimonial> testimonials,  List<Faq> faqs,  List<EventDocument> documents)  $default,) {final _that = this;
switch (_that) {
case _Event():
return $default(_that.id,_that.slug,_that.title,_that.description,_that.status,_that.startDate,_that.endDate,_that.dailyStartTime,_that.dailyEndTime,_that.venueName,_that.venueAddress,_that.posterImage,_that.logo,_that.videoUrl,_that.isRegistrationOpen,_that.registrationType,_that.registrationUrl,_that.yandexFormId,_that.mediaImage,_that.mediaDescription,_that.isMediaVisible,_that.gallery,_that.galleryExternalUrl,_that.galleryExternalDescription,_that.isGalleryExternalVisible,_that.days,_that.speakers,_that.guests,_that.testimonials,_that.faqs,_that.documents);case _:
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

@optionalTypeArgs TResult? whenOrNull<TResult extends Object?>(TResult? Function( int id,  String slug,  String title,  String? description,  String status,  DateTime? startDate,  DateTime? endDate,  String? dailyStartTime,  String? dailyEndTime,  String? venueName,  String? venueAddress,  String? posterImage,  String? logo,  String? videoUrl,  bool isRegistrationOpen,  String? registrationType,  String? registrationUrl,  String? yandexFormId,  String? mediaImage,  String? mediaDescription,  bool isMediaVisible,  List<String> gallery,  String? galleryExternalUrl,  String? galleryExternalDescription,  bool isGalleryExternalVisible,  List<EventDay> days,  List<Speaker> speakers,  List<Guest> guests,  List<Testimonial> testimonials,  List<Faq> faqs,  List<EventDocument> documents)?  $default,) {final _that = this;
switch (_that) {
case _Event() when $default != null:
return $default(_that.id,_that.slug,_that.title,_that.description,_that.status,_that.startDate,_that.endDate,_that.dailyStartTime,_that.dailyEndTime,_that.venueName,_that.venueAddress,_that.posterImage,_that.logo,_that.videoUrl,_that.isRegistrationOpen,_that.registrationType,_that.registrationUrl,_that.yandexFormId,_that.mediaImage,_that.mediaDescription,_that.isMediaVisible,_that.gallery,_that.galleryExternalUrl,_that.galleryExternalDescription,_that.isGalleryExternalVisible,_that.days,_that.speakers,_that.guests,_that.testimonials,_that.faqs,_that.documents);case _:
  return null;

}
}

}

/// @nodoc
@JsonSerializable()

class _Event implements Event {
  const _Event({required this.id, required this.slug, required this.title, this.description, required this.status, this.startDate, this.endDate, this.dailyStartTime, this.dailyEndTime, this.venueName, this.venueAddress, this.posterImage, this.logo, this.videoUrl, this.isRegistrationOpen = false, this.registrationType, this.registrationUrl, this.yandexFormId, this.mediaImage, this.mediaDescription, this.isMediaVisible = false,  List<String> gallery = const <String>[], this.galleryExternalUrl, this.galleryExternalDescription, this.isGalleryExternalVisible = false,  List<EventDay> days = const [],  List<Speaker> speakers = const [],  List<Guest> guests = const [],  List<Testimonial> testimonials = const [],  List<Faq> faqs = const [],  List<EventDocument> documents = const []}): _gallery = gallery,_days = days,_speakers = speakers,_guests = guests,_testimonials = testimonials,_faqs = faqs,_documents = documents;
  factory _Event.fromJson(Map<String, dynamic> json) => _$EventFromJson(json);

@override final  int id;
@override final  String slug;
@override final  String title;
@override final  String? description;
@override final  String status;
@override final  DateTime? startDate;
@override final  DateTime? endDate;
@override final  String? dailyStartTime;
@override final  String? dailyEndTime;
@override final  String? venueName;
@override final  String? venueAddress;
@override final  String? posterImage;
@override final  String? logo;
@override final  String? videoUrl;
@override@JsonKey() final  bool isRegistrationOpen;
@override final  String? registrationType;
@override final  String? registrationUrl;
@override final  String? yandexFormId;
@override final  String? mediaImage;
@override final  String? mediaDescription;
@override@JsonKey() final  bool isMediaVisible;
 final  List<String> _gallery;
@override@JsonKey() List<String> get gallery {
  if (_gallery is EqualUnmodifiableListView) return _gallery;
  // ignore: implicit_dynamic_type
  return EqualUnmodifiableListView(_gallery);
}

@override final  String? galleryExternalUrl;
@override final  String? galleryExternalDescription;
@override@JsonKey() final  bool isGalleryExternalVisible;
 final  List<EventDay> _days;
@override@JsonKey() List<EventDay> get days {
  if (_days is EqualUnmodifiableListView) return _days;
  // ignore: implicit_dynamic_type
  return EqualUnmodifiableListView(_days);
}

 final  List<Speaker> _speakers;
@override@JsonKey() List<Speaker> get speakers {
  if (_speakers is EqualUnmodifiableListView) return _speakers;
  // ignore: implicit_dynamic_type
  return EqualUnmodifiableListView(_speakers);
}

 final  List<Guest> _guests;
@override@JsonKey() List<Guest> get guests {
  if (_guests is EqualUnmodifiableListView) return _guests;
  // ignore: implicit_dynamic_type
  return EqualUnmodifiableListView(_guests);
}

 final  List<Testimonial> _testimonials;
@override@JsonKey() List<Testimonial> get testimonials {
  if (_testimonials is EqualUnmodifiableListView) return _testimonials;
  // ignore: implicit_dynamic_type
  return EqualUnmodifiableListView(_testimonials);
}

 final  List<Faq> _faqs;
@override@JsonKey() List<Faq> get faqs {
  if (_faqs is EqualUnmodifiableListView) return _faqs;
  // ignore: implicit_dynamic_type
  return EqualUnmodifiableListView(_faqs);
}

 final  List<EventDocument> _documents;
@override@JsonKey() List<EventDocument> get documents {
  if (_documents is EqualUnmodifiableListView) return _documents;
  // ignore: implicit_dynamic_type
  return EqualUnmodifiableListView(_documents);
}


/// Create a copy of Event
/// with the given fields replaced by the non-null parameter values.
@override @JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
_$EventCopyWith<_Event> get copyWith => __$EventCopyWithImpl<_Event>(this, _$identity);

@override
Map<String, dynamic> toJson() {
  return _$EventToJson(this, );
}

@override
bool operator ==(Object other) {
    return identical(this, other) || (other.runtimeType == runtimeType&&other is _Event&&(identical(other.id, id) || other.id == id)&&(identical(other.slug, slug) || other.slug == slug)&&(identical(other.title, title) || other.title == title)&&(identical(other.description, description) || other.description == description)&&(identical(other.status, status) || other.status == status)&&(identical(other.startDate, startDate) || other.startDate == startDate)&&(identical(other.endDate, endDate) || other.endDate == endDate)&&(identical(other.dailyStartTime, dailyStartTime) || other.dailyStartTime == dailyStartTime)&&(identical(other.dailyEndTime, dailyEndTime) || other.dailyEndTime == dailyEndTime)&&(identical(other.venueName, venueName) || other.venueName == venueName)&&(identical(other.venueAddress, venueAddress) || other.venueAddress == venueAddress)&&(identical(other.posterImage, posterImage) || other.posterImage == posterImage)&&(identical(other.logo, logo) || other.logo == logo)&&(identical(other.videoUrl, videoUrl) || other.videoUrl == videoUrl)&&(identical(other.isRegistrationOpen, isRegistrationOpen) || other.isRegistrationOpen == isRegistrationOpen)&&(identical(other.registrationType, registrationType) || other.registrationType == registrationType)&&(identical(other.registrationUrl, registrationUrl) || other.registrationUrl == registrationUrl)&&(identical(other.yandexFormId, yandexFormId) || other.yandexFormId == yandexFormId)&&(identical(other.mediaImage, mediaImage) || other.mediaImage == mediaImage)&&(identical(other.mediaDescription, mediaDescription) || other.mediaDescription == mediaDescription)&&(identical(other.isMediaVisible, isMediaVisible) || other.isMediaVisible == isMediaVisible)&&const DeepCollectionEquality().equals(other.gallery, _gallery)&&(identical(other.galleryExternalUrl, galleryExternalUrl) || other.galleryExternalUrl == galleryExternalUrl)&&(identical(other.galleryExternalDescription, galleryExternalDescription) || other.galleryExternalDescription == galleryExternalDescription)&&(identical(other.isGalleryExternalVisible, isGalleryExternalVisible) || other.isGalleryExternalVisible == isGalleryExternalVisible)&&const DeepCollectionEquality().equals(other.days, _days)&&const DeepCollectionEquality().equals(other.speakers, _speakers)&&const DeepCollectionEquality().equals(other.guests, _guests)&&const DeepCollectionEquality().equals(other.testimonials, _testimonials)&&const DeepCollectionEquality().equals(other.faqs, _faqs)&&const DeepCollectionEquality().equals(other.documents, _documents));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
    return Object.hashAll([runtimeType,id,slug,title,description,status,startDate,endDate,dailyStartTime,dailyEndTime,venueName,venueAddress,posterImage,logo,videoUrl,isRegistrationOpen,registrationType,registrationUrl,yandexFormId,mediaImage,mediaDescription,isMediaVisible,const DeepCollectionEquality().hash(_gallery),galleryExternalUrl,galleryExternalDescription,isGalleryExternalVisible,const DeepCollectionEquality().hash(_days),const DeepCollectionEquality().hash(_speakers),const DeepCollectionEquality().hash(_guests),const DeepCollectionEquality().hash(_testimonials),const DeepCollectionEquality().hash(_faqs),const DeepCollectionEquality().hash(_documents)]);
}

@override
String toString() {
    return 'Event(id: $id, slug: $slug, title: $title, description: $description, status: $status, startDate: $startDate, endDate: $endDate, dailyStartTime: $dailyStartTime, dailyEndTime: $dailyEndTime, venueName: $venueName, venueAddress: $venueAddress, posterImage: $posterImage, logo: $logo, videoUrl: $videoUrl, isRegistrationOpen: $isRegistrationOpen, registrationType: $registrationType, registrationUrl: $registrationUrl, yandexFormId: $yandexFormId, mediaImage: $mediaImage, mediaDescription: $mediaDescription, isMediaVisible: $isMediaVisible, gallery: $gallery, galleryExternalUrl: $galleryExternalUrl, galleryExternalDescription: $galleryExternalDescription, isGalleryExternalVisible: $isGalleryExternalVisible, days: $days, speakers: $speakers, guests: $guests, testimonials: $testimonials, faqs: $faqs, documents: $documents)';
}


}

/// @nodoc
abstract mixin class _$EventCopyWith<$Res> implements $EventCopyWith<$Res> {
  factory _$EventCopyWith(_Event value, $Res Function(_Event) _then) = __$EventCopyWithImpl;
@override @useResult
$Res call({
 int id, String slug, String title, String? description, String status, DateTime? startDate, DateTime? endDate, String? dailyStartTime, String? dailyEndTime, String? venueName, String? venueAddress, String? posterImage, String? logo, String? videoUrl, bool isRegistrationOpen, String? registrationType, String? registrationUrl, String? yandexFormId, String? mediaImage, String? mediaDescription, bool isMediaVisible, List<String> gallery, String? galleryExternalUrl, String? galleryExternalDescription, bool isGalleryExternalVisible, List<EventDay> days, List<Speaker> speakers, List<Guest> guests, List<Testimonial> testimonials, List<Faq> faqs, List<EventDocument> documents
});




}
/// @nodoc
class __$EventCopyWithImpl<$Res>
    implements _$EventCopyWith<$Res> {
  __$EventCopyWithImpl(this._self, this._then);

  final _Event _self;
  final $Res Function(_Event) _then;

/// Create a copy of Event
/// with the given fields replaced by the non-null parameter values.
@override @pragma('vm:prefer-inline') $Res call({Object? id = null,Object? slug = null,Object? title = null,Object? description = freezed,Object? status = null,Object? startDate = freezed,Object? endDate = freezed,Object? dailyStartTime = freezed,Object? dailyEndTime = freezed,Object? venueName = freezed,Object? venueAddress = freezed,Object? posterImage = freezed,Object? logo = freezed,Object? videoUrl = freezed,Object? isRegistrationOpen = null,Object? registrationType = freezed,Object? registrationUrl = freezed,Object? yandexFormId = freezed,Object? mediaImage = freezed,Object? mediaDescription = freezed,Object? isMediaVisible = null,Object? gallery = null,Object? galleryExternalUrl = freezed,Object? galleryExternalDescription = freezed,Object? isGalleryExternalVisible = null,Object? days = null,Object? speakers = null,Object? guests = null,Object? testimonials = null,Object? faqs = null,Object? documents = null,}) {
  return _then(_Event(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,slug: null == slug ? _self.slug : slug // ignore: cast_nullable_to_non_nullable
as String,title: null == title ? _self.title : title // ignore: cast_nullable_to_non_nullable
as String,description: freezed == description ? _self.description : description // ignore: cast_nullable_to_non_nullable
as String?,status: null == status ? _self.status : status // ignore: cast_nullable_to_non_nullable
as String,startDate: freezed == startDate ? _self.startDate : startDate // ignore: cast_nullable_to_non_nullable
as DateTime?,endDate: freezed == endDate ? _self.endDate : endDate // ignore: cast_nullable_to_non_nullable
as DateTime?,dailyStartTime: freezed == dailyStartTime ? _self.dailyStartTime : dailyStartTime // ignore: cast_nullable_to_non_nullable
as String?,dailyEndTime: freezed == dailyEndTime ? _self.dailyEndTime : dailyEndTime // ignore: cast_nullable_to_non_nullable
as String?,venueName: freezed == venueName ? _self.venueName : venueName // ignore: cast_nullable_to_non_nullable
as String?,venueAddress: freezed == venueAddress ? _self.venueAddress : venueAddress // ignore: cast_nullable_to_non_nullable
as String?,posterImage: freezed == posterImage ? _self.posterImage : posterImage // ignore: cast_nullable_to_non_nullable
as String?,logo: freezed == logo ? _self.logo : logo // ignore: cast_nullable_to_non_nullable
as String?,videoUrl: freezed == videoUrl ? _self.videoUrl : videoUrl // ignore: cast_nullable_to_non_nullable
as String?,isRegistrationOpen: null == isRegistrationOpen ? _self.isRegistrationOpen : isRegistrationOpen // ignore: cast_nullable_to_non_nullable
as bool,registrationType: freezed == registrationType ? _self.registrationType : registrationType // ignore: cast_nullable_to_non_nullable
as String?,registrationUrl: freezed == registrationUrl ? _self.registrationUrl : registrationUrl // ignore: cast_nullable_to_non_nullable
as String?,yandexFormId: freezed == yandexFormId ? _self.yandexFormId : yandexFormId // ignore: cast_nullable_to_non_nullable
as String?,mediaImage: freezed == mediaImage ? _self.mediaImage : mediaImage // ignore: cast_nullable_to_non_nullable
as String?,mediaDescription: freezed == mediaDescription ? _self.mediaDescription : mediaDescription // ignore: cast_nullable_to_non_nullable
as String?,isMediaVisible: null == isMediaVisible ? _self.isMediaVisible : isMediaVisible // ignore: cast_nullable_to_non_nullable
as bool,gallery: null == gallery ? _self._gallery : gallery // ignore: cast_nullable_to_non_nullable
as List<String>,galleryExternalUrl: freezed == galleryExternalUrl ? _self.galleryExternalUrl : galleryExternalUrl // ignore: cast_nullable_to_non_nullable
as String?,galleryExternalDescription: freezed == galleryExternalDescription ? _self.galleryExternalDescription : galleryExternalDescription // ignore: cast_nullable_to_non_nullable
as String?,isGalleryExternalVisible: null == isGalleryExternalVisible ? _self.isGalleryExternalVisible : isGalleryExternalVisible // ignore: cast_nullable_to_non_nullable
as bool,days: null == days ? _self._days : days // ignore: cast_nullable_to_non_nullable
as List<EventDay>,speakers: null == speakers ? _self._speakers : speakers // ignore: cast_nullable_to_non_nullable
as List<Speaker>,guests: null == guests ? _self._guests : guests // ignore: cast_nullable_to_non_nullable
as List<Guest>,testimonials: null == testimonials ? _self._testimonials : testimonials // ignore: cast_nullable_to_non_nullable
as List<Testimonial>,faqs: null == faqs ? _self._faqs : faqs // ignore: cast_nullable_to_non_nullable
as List<Faq>,documents: null == documents ? _self._documents : documents // ignore: cast_nullable_to_non_nullable
as List<EventDocument>,
  ));
}


}

// dart format on
