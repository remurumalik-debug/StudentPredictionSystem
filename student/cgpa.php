<?php
require_once __DIR__ . '/../includes/functions.php';
$user = require_role(['Student']);
$title = 'CGPA Calculator';
require __DIR__ . '/../views/partials/header.php';
?>
<div class="card p-3">
    <h2 class="h6">Automatic CGPA Calculator</h2>
    <div class="row g-2" id="cgpaRows"></div>
    <button class="btn btn-outline-primary mt-3" id="addCgpaRow" type="button"><i class="bi bi-plus-lg"></i> Add Course</button>
    <div class="alert alert-info mt-3">CGPA: <strong id="cgpaValue">0.00</strong></div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const rows = document.getElementById('cgpaRows'), out = document.getElementById('cgpaValue');
  function calc(){let points=0, units=0; rows.querySelectorAll('.cgpa-row').forEach(r=>{const u=+r.querySelector('.unit').value||0,g=+r.querySelector('.grade').value||0; points+=u*g; units+=u}); out.textContent=units?(points/units).toFixed(2):'0.00'}
  function add(){rows.insertAdjacentHTML('beforeend','<div class="col-md-6 cgpa-row"><div class="input-group"><input class="form-control" placeholder="Course"><input class="form-control unit" type="number" value="3" min="1"><select class="form-select grade"><option value="5">A</option><option value="4">B</option><option value="3">C</option><option value="2">D</option><option value="1">E</option><option value="0">F</option></select></div></div>'); rows.querySelectorAll('input,select').forEach(el=>el.oninput=calc); calc()}
  document.getElementById('addCgpaRow').onclick=add; add(); add(); add();
});
</script>
<?php require __DIR__ . '/../views/partials/footer.php'; ?>
