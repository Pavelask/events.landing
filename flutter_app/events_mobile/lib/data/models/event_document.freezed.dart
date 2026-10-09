// GENERATED CODE - DO NOT MODIFY BY HAND
// coverage:ignore-file
// ignore_for_file: type=lint, type=warning, deprecated_member_use, deprecated_member_use_from_same_package
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'event_document.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

// GENERATED CODE - DO NOT MODIFY BY HAND
// dart format off
T _$identity<T>(T value) => value;

/// @nodoc
mixin _$EventDocument {

 int get id; String get title; String? get filePath; String? get fileType;
/// Create a copy of EventDocument
/// with the given fields replaced by the non-null parameter values.
@JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
$EventDocumentCopyWith<EventDocument> get copyWith => _$EventDocumentCopyWithImpl<EventDocument>(this as EventDocument, _$identity);

  /// Serializes this EventDocument to a JSON map.
  Map<String, dynamic> toJson();


@override
bool operator ==(Object other) {
  final _this = this as EventDocument;
  return identical(this, other) || (other.runtimeType == runtimeType&&other is EventDocument&&(identical(other.id, _this.id) || other.id == _this.id)&&(identical(other.title, _this.title) || other.title == _this.title)&&(identical(other.filePath, _this.filePath) || other.filePath == _this.filePath)&&(identical(other.fileType, _this.fileType) || other.fileType == _this.fileType));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
  final _this = this as EventDocument;
  return Object.hash(runtimeType,_this.id,_this.title,_this.filePath,_this.fileType);
}

@override
String toString() {
  final _this = this as EventDocument;
  return 'EventDocument(id: ${_this.id}, title: ${_this.title}, filePath: ${_this.filePath}, fileType: ${_this.fileType})';
}


}

/// @nodoc
abstract mixin class $EventDocumentCopyWith<$Res>  {
  factory $EventDocumentCopyWith(EventDocument value, $Res Function(EventDocument) _then) = _$EventDocumentCopyWithImpl;
@useResult
$Res call({
 int id, String title, String? filePath, String? fileType
});




}
/// @nodoc
class _$EventDocumentCopyWithImpl<$Res>
    implements $EventDocumentCopyWith<$Res> {
  _$EventDocumentCopyWithImpl(this._self, this._then);

  final EventDocument _self;
  final $Res Function(EventDocument) _then;

/// Create a copy of EventDocument
/// with the given fields replaced by the non-null parameter values.
@pragma('vm:prefer-inline') @override $Res call({Object? id = null,Object? title = null,Object? filePath = freezed,Object? fileType = freezed,}) {
  return _then(EventDocument(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,title: null == title ? _self.title : title // ignore: cast_nullable_to_non_nullable
as String,filePath: freezed == filePath ? _self.filePath : filePath // ignore: cast_nullable_to_non_nullable
as String?,fileType: freezed == fileType ? _self.fileType : fileType // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}

}


/// Adds pattern-matching-related methods to [EventDocument].
extension EventDocumentPatterns on EventDocument {
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

@optionalTypeArgs TResult maybeMap<TResult extends Object?>(TResult Function( _EventDocument value)?  $default,{required TResult orElse(),}){
final _that = this;
switch (_that) {
case _EventDocument() when $default != null:
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

@optionalTypeArgs TResult map<TResult extends Object?>(TResult Function( _EventDocument value)  $default,){
final _that = this;
switch (_that) {
case _EventDocument():
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

@optionalTypeArgs TResult? mapOrNull<TResult extends Object?>(TResult? Function( _EventDocument value)?  $default,){
final _that = this;
switch (_that) {
case _EventDocument() when $default != null:
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

@optionalTypeArgs TResult maybeWhen<TResult extends Object?>(TResult Function( int id,  String title,  String? filePath,  String? fileType)?  $default,{required TResult orElse(),}) {final _that = this;
switch (_that) {
case _EventDocument() when $default != null:
return $default(_that.id,_that.title,_that.filePath,_that.fileType);case _:
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

@optionalTypeArgs TResult when<TResult extends Object?>(TResult Function( int id,  String title,  String? filePath,  String? fileType)  $default,) {final _that = this;
switch (_that) {
case _EventDocument():
return $default(_that.id,_that.title,_that.filePath,_that.fileType);case _:
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

@optionalTypeArgs TResult? whenOrNull<TResult extends Object?>(TResult? Function( int id,  String title,  String? filePath,  String? fileType)?  $default,) {final _that = this;
switch (_that) {
case _EventDocument() when $default != null:
return $default(_that.id,_that.title,_that.filePath,_that.fileType);case _:
  return null;

}
}

}

/// @nodoc
@JsonSerializable()

class _EventDocument implements EventDocument {
  const _EventDocument({required this.id, required this.title, this.filePath, this.fileType});
  factory _EventDocument.fromJson(Map<String, dynamic> json) => _$EventDocumentFromJson(json);

@override final  int id;
@override final  String title;
@override final  String? filePath;
@override final  String? fileType;

/// Create a copy of EventDocument
/// with the given fields replaced by the non-null parameter values.
@override @JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
_$EventDocumentCopyWith<_EventDocument> get copyWith => __$EventDocumentCopyWithImpl<_EventDocument>(this, _$identity);

@override
Map<String, dynamic> toJson() {
  return _$EventDocumentToJson(this, );
}

@override
bool operator ==(Object other) {
    return identical(this, other) || (other.runtimeType == runtimeType&&other is _EventDocument&&(identical(other.id, id) || other.id == id)&&(identical(other.title, title) || other.title == title)&&(identical(other.filePath, filePath) || other.filePath == filePath)&&(identical(other.fileType, fileType) || other.fileType == fileType));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
    return Object.hash(runtimeType,id,title,filePath,fileType);
}

@override
String toString() {
    return 'EventDocument(id: $id, title: $title, filePath: $filePath, fileType: $fileType)';
}


}

/// @nodoc
abstract mixin class _$EventDocumentCopyWith<$Res> implements $EventDocumentCopyWith<$Res> {
  factory _$EventDocumentCopyWith(_EventDocument value, $Res Function(_EventDocument) _then) = __$EventDocumentCopyWithImpl;
@override @useResult
$Res call({
 int id, String title, String? filePath, String? fileType
});




}
/// @nodoc
class __$EventDocumentCopyWithImpl<$Res>
    implements _$EventDocumentCopyWith<$Res> {
  __$EventDocumentCopyWithImpl(this._self, this._then);

  final _EventDocument _self;
  final $Res Function(_EventDocument) _then;

/// Create a copy of EventDocument
/// with the given fields replaced by the non-null parameter values.
@override @pragma('vm:prefer-inline') $Res call({Object? id = null,Object? title = null,Object? filePath = freezed,Object? fileType = freezed,}) {
  return _then(_EventDocument(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,title: null == title ? _self.title : title // ignore: cast_nullable_to_non_nullable
as String,filePath: freezed == filePath ? _self.filePath : filePath // ignore: cast_nullable_to_non_nullable
as String?,fileType: freezed == fileType ? _self.fileType : fileType // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}


}

// dart format on
