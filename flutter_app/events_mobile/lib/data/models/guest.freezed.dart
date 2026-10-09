// GENERATED CODE - DO NOT MODIFY BY HAND
// coverage:ignore-file
// ignore_for_file: type=lint, type=warning, deprecated_member_use, deprecated_member_use_from_same_package
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'guest.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

// GENERATED CODE - DO NOT MODIFY BY HAND
// dart format off
T _$identity<T>(T value) => value;

/// @nodoc
mixin _$Guest {

 int get id; String get name; String? get position; String? get organization; String? get description; String? get photo;
/// Create a copy of Guest
/// with the given fields replaced by the non-null parameter values.
@JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
$GuestCopyWith<Guest> get copyWith => _$GuestCopyWithImpl<Guest>(this as Guest, _$identity);

  /// Serializes this Guest to a JSON map.
  Map<String, dynamic> toJson();


@override
bool operator ==(Object other) {
  final _this = this as Guest;
  return identical(this, other) || (other.runtimeType == runtimeType&&other is Guest&&(identical(other.id, _this.id) || other.id == _this.id)&&(identical(other.name, _this.name) || other.name == _this.name)&&(identical(other.position, _this.position) || other.position == _this.position)&&(identical(other.organization, _this.organization) || other.organization == _this.organization)&&(identical(other.description, _this.description) || other.description == _this.description)&&(identical(other.photo, _this.photo) || other.photo == _this.photo));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
  final _this = this as Guest;
  return Object.hash(runtimeType,_this.id,_this.name,_this.position,_this.organization,_this.description,_this.photo);
}

@override
String toString() {
  final _this = this as Guest;
  return 'Guest(id: ${_this.id}, name: ${_this.name}, position: ${_this.position}, organization: ${_this.organization}, description: ${_this.description}, photo: ${_this.photo})';
}


}

/// @nodoc
abstract mixin class $GuestCopyWith<$Res>  {
  factory $GuestCopyWith(Guest value, $Res Function(Guest) _then) = _$GuestCopyWithImpl;
@useResult
$Res call({
 int id, String name, String? position, String? organization, String? description, String? photo
});




}
/// @nodoc
class _$GuestCopyWithImpl<$Res>
    implements $GuestCopyWith<$Res> {
  _$GuestCopyWithImpl(this._self, this._then);

  final Guest _self;
  final $Res Function(Guest) _then;

/// Create a copy of Guest
/// with the given fields replaced by the non-null parameter values.
@pragma('vm:prefer-inline') @override $Res call({Object? id = null,Object? name = null,Object? position = freezed,Object? organization = freezed,Object? description = freezed,Object? photo = freezed,}) {
  return _then(Guest(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,name: null == name ? _self.name : name // ignore: cast_nullable_to_non_nullable
as String,position: freezed == position ? _self.position : position // ignore: cast_nullable_to_non_nullable
as String?,organization: freezed == organization ? _self.organization : organization // ignore: cast_nullable_to_non_nullable
as String?,description: freezed == description ? _self.description : description // ignore: cast_nullable_to_non_nullable
as String?,photo: freezed == photo ? _self.photo : photo // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}

}


/// Adds pattern-matching-related methods to [Guest].
extension GuestPatterns on Guest {
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

@optionalTypeArgs TResult maybeMap<TResult extends Object?>(TResult Function( _Guest value)?  $default,{required TResult orElse(),}){
final _that = this;
switch (_that) {
case _Guest() when $default != null:
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

@optionalTypeArgs TResult map<TResult extends Object?>(TResult Function( _Guest value)  $default,){
final _that = this;
switch (_that) {
case _Guest():
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

@optionalTypeArgs TResult? mapOrNull<TResult extends Object?>(TResult? Function( _Guest value)?  $default,){
final _that = this;
switch (_that) {
case _Guest() when $default != null:
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

@optionalTypeArgs TResult maybeWhen<TResult extends Object?>(TResult Function( int id,  String name,  String? position,  String? organization,  String? description,  String? photo)?  $default,{required TResult orElse(),}) {final _that = this;
switch (_that) {
case _Guest() when $default != null:
return $default(_that.id,_that.name,_that.position,_that.organization,_that.description,_that.photo);case _:
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

@optionalTypeArgs TResult when<TResult extends Object?>(TResult Function( int id,  String name,  String? position,  String? organization,  String? description,  String? photo)  $default,) {final _that = this;
switch (_that) {
case _Guest():
return $default(_that.id,_that.name,_that.position,_that.organization,_that.description,_that.photo);case _:
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

@optionalTypeArgs TResult? whenOrNull<TResult extends Object?>(TResult? Function( int id,  String name,  String? position,  String? organization,  String? description,  String? photo)?  $default,) {final _that = this;
switch (_that) {
case _Guest() when $default != null:
return $default(_that.id,_that.name,_that.position,_that.organization,_that.description,_that.photo);case _:
  return null;

}
}

}

/// @nodoc
@JsonSerializable()

class _Guest implements Guest {
  const _Guest({required this.id, required this.name, this.position, this.organization, this.description, this.photo});
  factory _Guest.fromJson(Map<String, dynamic> json) => _$GuestFromJson(json);

@override final  int id;
@override final  String name;
@override final  String? position;
@override final  String? organization;
@override final  String? description;
@override final  String? photo;

/// Create a copy of Guest
/// with the given fields replaced by the non-null parameter values.
@override @JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
_$GuestCopyWith<_Guest> get copyWith => __$GuestCopyWithImpl<_Guest>(this, _$identity);

@override
Map<String, dynamic> toJson() {
  return _$GuestToJson(this, );
}

@override
bool operator ==(Object other) {
    return identical(this, other) || (other.runtimeType == runtimeType&&other is _Guest&&(identical(other.id, id) || other.id == id)&&(identical(other.name, name) || other.name == name)&&(identical(other.position, position) || other.position == position)&&(identical(other.organization, organization) || other.organization == organization)&&(identical(other.description, description) || other.description == description)&&(identical(other.photo, photo) || other.photo == photo));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
    return Object.hash(runtimeType,id,name,position,organization,description,photo);
}

@override
String toString() {
    return 'Guest(id: $id, name: $name, position: $position, organization: $organization, description: $description, photo: $photo)';
}


}

/// @nodoc
abstract mixin class _$GuestCopyWith<$Res> implements $GuestCopyWith<$Res> {
  factory _$GuestCopyWith(_Guest value, $Res Function(_Guest) _then) = __$GuestCopyWithImpl;
@override @useResult
$Res call({
 int id, String name, String? position, String? organization, String? description, String? photo
});




}
/// @nodoc
class __$GuestCopyWithImpl<$Res>
    implements _$GuestCopyWith<$Res> {
  __$GuestCopyWithImpl(this._self, this._then);

  final _Guest _self;
  final $Res Function(_Guest) _then;

/// Create a copy of Guest
/// with the given fields replaced by the non-null parameter values.
@override @pragma('vm:prefer-inline') $Res call({Object? id = null,Object? name = null,Object? position = freezed,Object? organization = freezed,Object? description = freezed,Object? photo = freezed,}) {
  return _then(_Guest(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,name: null == name ? _self.name : name // ignore: cast_nullable_to_non_nullable
as String,position: freezed == position ? _self.position : position // ignore: cast_nullable_to_non_nullable
as String?,organization: freezed == organization ? _self.organization : organization // ignore: cast_nullable_to_non_nullable
as String?,description: freezed == description ? _self.description : description // ignore: cast_nullable_to_non_nullable
as String?,photo: freezed == photo ? _self.photo : photo // ignore: cast_nullable_to_non_nullable
as String?,
  ));
}


}

// dart format on
