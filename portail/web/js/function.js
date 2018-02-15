$(".alert").delay(7000).slideUp(200, function() {
    $(this).alert('close');
});

function disableAllWrongOptions1() {
    var yearGroup = document.getElementById("yearGroupSelect1");
    var selectedYearGroup = yearGroup.options[ yearGroup.selectedIndex ].value;
    var subGroups = document.getElementById("subGroupsSelect1");
    var regexp = new RegExp('^'+selectedYearGroup+'_.+$');
    var i;
    for (i = 0; i < subGroups.length; i++) {
        if (regexp.test(subGroups.options[i].value)) {
            subGroups.options[i].disabled = false;
        } else {
            subGroups.options[i].disabled = true;
        }
    }
}

function disableAllWrongOptions2() {
    var yearGroup = document.getElementById("yearGroupSelect2");
    var selectedYearGroup = yearGroup.options[ yearGroup.selectedIndex ].value;
    var subGroups = document.getElementById("subGroupsSelect2");
    var regexp = new RegExp('^'+selectedYearGroup+'_.+$');
    var i;
    for (i = 0; i < subGroups.length; i++) {
        if (regexp.test(subGroups.options[i].value)) {
            subGroups.options[i].disabled = false;
        } else {
            subGroups.options[i].disabled = true;
        }
    }
}
