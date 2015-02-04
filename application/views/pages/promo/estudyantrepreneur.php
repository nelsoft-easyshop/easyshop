<select id="ddown-school">
    <?PHP foreach($schools_and_students as $school => $students) : ?>
    <option value="<?=html_escape(json_encode($students))?>"><?=html_escape($school)?></option>
    <?PHP endforeach; ?>
</select>
<div id="student-container">

</div>
<button id="btn-vote"> VOTE </button>
<script type="text/javascript" src="/assets/js/src/promom/estudyantrepreneur.js"></script>
