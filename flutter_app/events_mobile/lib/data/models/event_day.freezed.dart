// GENERATED CODE - DO NOT MODIFY BY HAND
// coverage:ignore-file
// ignore_for_file: type=lint, type=warning, deprecated_member_use, deprecated_member_use_from_same_package
// ignore_for_file: unused_element, deprecated_member_use, deprecated_member_use_from_same_package, use_function_type_syntax_for_parameters, unnecessary_const, avoid_init_to_null, invalid_override_different_default_values_named, prefer_expression_function_bodies, annotate_overrides, invalid_annotation_target, unnecessary_question_mark

part of 'event_day.dart';

// **************************************************************************
// FreezedGenerator
// **************************************************************************

// GENERATED CODE - DO NOT MODIFY BY HAND
// dart format off
T _$identity<T>(T value) => value;

/// @nodoc
mixin _$EventDay {

 int get id; DateTime? get date; String? get label; String? get description; int? get sortOrder; List<ScheduleEvent> get events;
/// Create a copy of EventDay
/// with the given fields replaced by the non-null parameter values.
@JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
$EventDayCopyWith<EventDay> get copyWith => _$EventDayCopyWithImpl<EventDay>(this as EventDay, _$identity);

  /// Serializes this EventDay to a JSON map.
  Map<String, dynamic> toJson();


@override
bool operator ==(Object other) {
  final _this = this as EventDay;
  return identical(this, other) || (other.runtimeType == runtimeType&&other is EventDay&&(identical(other.id, _this.id) || other.id == _this.id)&&(identical(other.date, _this.date) || other.date == _this.date)&&(identical(other.label, _this.label) || other.label == _this.label)&&(identical(other.description, _this.description) || other.description == _this.description)&&(identical(other.sortOrder, _this.sortOrder) || other.sortOrder == _this.sortOrder)&&const DeepCollectionEquality().equals(other.events, _this.events));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
  final _this = this as EventDay;
  return Object.hash(runtimeType,_this.id,_this.date,_this.label,_this.description,_this.sortOrder,const DeepCollectionEquality().hash(_this.events));
}

@override
String toString() {
  final _this = this as EventDay;
  return 'EventDay(id: ${_this.id}, date: ${_this.date}, label: ${_this.label}, description: ${_this.description}, sortOrder: ${_this.sortOrder}, events: ${_this.events})';
}


}

/// @nodoc
abstract mixin class $EventDayCopyWith<$Res>  {
  factory $EventDayCopyWith(EventDay value, $Res Function(EventDay) _then) = _$EventDayCopyWithImpl;
@useResult
$Res call({
 int id, DateTime? date, String? label, String? description, int? sortOrder, List<ScheduleEvent> events
});




}
/// @nodoc
class _$EventDayCopyWithImpl<$Res>
    implements $EventDayCopyWith<$Res> {
  _$EventDayCopyWithImpl(this._self, this._then);

  final EventDay _self;
  final $Res Function(EventDay) _then;

/// Create a copy of EventDay
/// with the given fields replaced by the non-null parameter values.
@pragma('vm:prefer-inline') @override $Res call({Object? id = null,Object? date = freezed,Object? label = freezed,Object? description = freezed,Object? sortOrder = freezed,Object? events = null,}) {
  return _then(EventDay(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,date: freezed == date ? _self.date : date // ignore: cast_nullable_to_non_nullable
as DateTime?,label: freezed == label ? _self.label : label // ignore: cast_nullable_to_non_nullable
as String?,description: freezed == description ? _self.description : description // ignore: cast_nullable_to_non_nullable
as String?,sortOrder: freezed == sortOrder ? _self.sortOrder : sortOrder // ignore: cast_nullable_to_non_nullable
as int?,events: null == events ? _self.events : events // ignore: cast_nullable_to_non_nullable
as List<ScheduleEvent>,
  ));
}

}


/// Adds pattern-matching-related methods to [EventDay].
extension EventDayPatterns on EventDay {
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

@optionalTypeArgs TResult maybeMap<TResult extends Object?>(TResult Function( _EventDay value)?  $default,{required TResult orElse(),}){
final _that = this;
switch (_that) {
case _EventDay() when $default != null:
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

@optionalTypeArgs TResult map<TResult extends Object?>(TResult Function( _EventDay value)  $default,){
final _that = this;
switch (_that) {
case _EventDay():
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

@optionalTypeArgs TResult? mapOrNull<TResult extends Object?>(TResult? Function( _EventDay value)?  $default,){
final _that = this;
switch (_that) {
case _EventDay() when $default != null:
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

@optionalTypeArgs TResult maybeWhen<TResult extends Object?>(TResult Function( int id,  DateTime? date,  String? label,  String? description,  int? sortOrder,  List<ScheduleEvent> events)?  $default,{required TResult orElse(),}) {final _that = this;
switch (_that) {
case _EventDay() when $default != null:
return $default(_that.id,_that.date,_that.label,_that.description,_that.sortOrder,_that.events);case _:
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

@optionalTypeArgs TResult when<TResult extends Object?>(TResult Function( int id,  DateTime? date,  String? label,  String? description,  int? sortOrder,  List<ScheduleEvent> events)  $default,) {final _that = this;
switch (_that) {
case _EventDay():
return $default(_that.id,_that.date,_that.label,_that.description,_that.sortOrder,_that.events);case _:
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

@optionalTypeArgs TResult? whenOrNull<TResult extends Object?>(TResult? Function( int id,  DateTime? date,  String? label,  String? description,  int? sortOrder,  List<ScheduleEvent> events)?  $default,) {final _that = this;
switch (_that) {
case _EventDay() when $default != null:
return $default(_that.id,_that.date,_that.label,_that.description,_that.sortOrder,_that.events);case _:
  return null;

}
}

}

/// @nodoc
@JsonSerializable()

class _EventDay implements EventDay {
  const _EventDay({required this.id, this.date, this.label, this.description, this.sortOrder,  List<ScheduleEvent> events = const []}): _events = events;
  factory _EventDay.fromJson(Map<String, dynamic> json) => _$EventDayFromJson(json);

@override final  int id;
@override final  DateTime? date;
@override final  String? label;
@override final  String? description;
@override final  int? sortOrder;
 final  List<ScheduleEvent> _events;
@override@JsonKey() List<ScheduleEvent> get events {
  if (_events is EqualUnmodifiableListView) return _events;
  // ignore: implicit_dynamic_type
  return EqualUnmodifiableListView(_events);
}


/// Create a copy of EventDay
/// with the given fields replaced by the non-null parameter values.
@override @JsonKey(includeFromJson: false, includeToJson: false)
@pragma('vm:prefer-inline')
_$EventDayCopyWith<_EventDay> get copyWith => __$EventDayCopyWithImpl<_EventDay>(this, _$identity);

@override
Map<String, dynamic> toJson() {
  return _$EventDayToJson(this, );
}

@override
bool operator ==(Object other) {
    return identical(this, other) || (other.runtimeType == runtimeType&&other is _EventDay&&(identical(other.id, id) || other.id == id)&&(identical(other.date, date) || other.date == date)&&(identical(other.label, label) || other.label == label)&&(identical(other.description, description) || other.description == description)&&(identical(other.sortOrder, sortOrder) || other.sortOrder == sortOrder)&&const DeepCollectionEquality().equals(other.events, _events));
}

@JsonKey(includeFromJson: false, includeToJson: false)
@override
int get hashCode {
    return Object.hash(runtimeType,id,date,label,description,sortOrder,const DeepCollectionEquality().hash(_events));
}

@override
String toString() {
    return 'EventDay(id: $id, date: $date, label: $label, description: $description, sortOrder: $sortOrder, events: $events)';
}


}

/// @nodoc
abstract mixin class _$EventDayCopyWith<$Res> implements $EventDayCopyWith<$Res> {
  factory _$EventDayCopyWith(_EventDay value, $Res Function(_EventDay) _then) = __$EventDayCopyWithImpl;
@override @useResult
$Res call({
 int id, DateTime? date, String? label, String? description, int? sortOrder, List<ScheduleEvent> events
});




}
/// @nodoc
class __$EventDayCopyWithImpl<$Res>
    implements _$EventDayCopyWith<$Res> {
  __$EventDayCopyWithImpl(this._self, this._then);

  final _EventDay _self;
  final $Res Function(_EventDay) _then;

/// Create a copy of EventDay
/// with the given fields replaced by the non-null parameter values.
@override @pragma('vm:prefer-inline') $Res call({Object? id = null,Object? date = freezed,Object? label = freezed,Object? description = freezed,Object? sortOrder = freezed,Object? events = null,}) {
  return _then(_EventDay(
id: null == id ? _self.id : id // ignore: cast_nullable_to_non_nullable
as int,date: freezed == date ? _self.date : date // ignore: cast_nullable_to_non_nullable
as DateTime?,label: freezed == label ? _self.label : label // ignore: cast_nullable_to_non_nullable
as String?,description: freezed == description ? _self.description : description // ignore: cast_nullable_to_non_nullable
as String?,sortOrder: freezed == sortOrder ? _self.sortOrder : sortOrder // ignore: cast_nullable_to_non_nullable
as int?,events: null == events ? _self._events : events // ignore: cast_nullable_to_non_nullable
as List<ScheduleEvent>,
  ));
}


}

// dart format on
