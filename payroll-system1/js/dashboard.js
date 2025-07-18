const selectedRows = new Set();

  document.querySelectorAll('tbody tr').forEach(row => {
    row.addEventListener('click', (event) => {
      // Do not trigger row selection if a button or link inside the row is clicked
      if (event.target.tagName === 'A' || event.target.tagName === 'BUTTON' || event.target.closest('a') || event.target.closest('button')) {
        return;
      }

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

  function payslip(id) {
    // If an ID is passed directly (from a row button), use it.
    if (id !== undefined && id !== null) {
      window.location.href = `payslip.php?id=${id}`;
      return;
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
