String stripHtml(String? input) {
  if (input == null || input.isEmpty) return '';
  var text = input
      .replaceAll(RegExp(r'<br\s*/?>', caseSensitive: false), '\n')
      .replaceAll(RegExp(r'</p>', caseSensitive: false), '\n\n')
      .replaceAll(RegExp(r'<[^>]*>'), '')
      .replaceAll('&nbsp;', ' ')
      .replaceAll('&amp;', '&')
      .replaceAll('&laquo;', '«')
      .replaceAll('&raquo;', '»')
      .replaceAll('&mdash;', '—')
      .replaceAll('&quot;', '"');
  return text.replaceAll(RegExp(r'\n{3,}'), '\n\n').trim();
}
