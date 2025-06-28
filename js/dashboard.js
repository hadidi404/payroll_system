const selectedRows = new Set();

  document.querySelectorAll('tbody tr').forEach(row => {
    row.addEventListener('click', () => {
      const id = row.getAttribute('data-id');

      if (selectedRows.has(id)) {
        row.classList.remove('selected');
        selectedRows.clear();
      } else {
        clearSelections();
        row.classList.add('selected');
        selectedRows.add(id);
      }

      updateSelectedInput();
    });
  });

    function enterDeleteMode() {
    mode = 'multiple';
    document.getElementById('deselect_btn').style.display = 'inline-block';

    if (selectedRows.size === 0) {
      alert("Select employees to delete by clicking their rows. Click 'Deselect All' to cancel.");
      return;
    }

    const ids = Array.from(selectedRows);
    const confirmDelete = confirm(`Are you sure you want to delete ${ids.length} employee(s)?`);
    if (confirmDelete) {
      window.location.href = `delete.php?ids=${ids.join(',')}`;
    }
  }

  function updateSelectedInput() {
    document.getElementById('selected_ids').value = Array.from(selectedRows).join(',');
  }

  function clearSelections() {
    selectedRows.clear();
    document.querySelectorAll('tr.selected').forEach(row => row.classList.remove('selected'));
    updateSelectedInput();
  }

  function payslip() {
    if (selectedRows.size === 1) {
      const id = Array.from(selectedRows)[0];
      window.location.href = `export.php?id=${id}`;
    } else if (selectedRows.size === 0) {
      alert("Please select an employee to generate payslip.");
    } else {
      alert("Please select only one employee to generate payslip.");
    }
  }
function openTab(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(tabName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Set the first tab as active by default
document.getElementsByClassName("tablinks")[0].click();
