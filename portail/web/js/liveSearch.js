$('#searchUsers').on('keyup', function() {
  var value = $(this).val();
  var patt = new RegExp(value, "i");

  $('#tableUsers').find('tr').each(function() {
    if (!($(this).find('td').text().search(patt) >= 0)) {
      $(this).not('.head').hide();
    }
    if (($(this).find('td').text().search(patt) >= 0)) {
      $(this).show();
    }
  });
});

$('#searchYearGroups').on('keyup', function() {
  var value = $(this).val();
  var patt = new RegExp(value, "i");

  $('#tableYearGroups').find('tr').each(function() {
    if (!($(this).find('td').text().search(patt) >= 0)) {
      $(this).not('.head').hide();
    }
    if (($(this).find('td').text().search(patt) >= 0)) {
      $(this).show();
    }
  });
});

$('#searchSubGroups').on('keyup', function() {
  var value = $(this).val();
  var patt = new RegExp(value, "i");

  $('#tableSubGroups').find('tr').each(function() {
    if (!($(this).find('td').text().search(patt) >= 0)) {
      $(this).not('.head').hide();
    }
    if (($(this).find('td').text().search(patt) >= 0)) {
      $(this).show();
    }
  });
});

$('#searchNotebooks').on('keyup', function() {
  var value = $(this).val();
  var patt = new RegExp(value, "i");

  $('#tableNotebooks').find('tr').each(function() {
    if (!($(this).find('td').text().search(patt) >= 0)) {
      $(this).not('.head').hide();
    }
    if (($(this).find('td').text().search(patt) >= 0)) {
      $(this).show();
    }
  });
});

$('#searchAssignments').on('keyup', function() {
  var value = $(this).val();
  var patt = new RegExp(value, "i");

  $('#tableAssignments').find('tr').each(function() {
    if (!($(this).find('td').text().search(patt) >= 0)) {
      $(this).not('.head').hide();
    }
    if (($(this).find('td').text().search(patt) >= 0)) {
      $(this).show();
    }
  });
});
