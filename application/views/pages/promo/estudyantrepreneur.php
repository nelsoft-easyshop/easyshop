<select id="ddown-school">
    <option value=""></option>
    <?PHP foreach($schools_and_students as $school => $students) : ?>
    <option value="<?=html_escape(json_encode($students))?>" data-id="<?=html_escape(str_replace(' ', '-', $school))?>"><?=html_escape($school)?></option>
    <?PHP endforeach; ?>
</select>
<div id="student-container">
    <?PHP foreach ($schools_and_students as $school => $students) : ?>
        <div id="<?=html_escape(str_replace(' ', '-', $school))?>" style="border: 1px black solid;display: none" class="display-none">
        <?PHP foreach ($students as $student) : ?>
                <input name="student" type="radio" value="<?=html_escape($student['idStudent'])?>"><?=html_escape($student['student'])?> <br>
        <?PHP endforeach; ?>
        </div>
        <br>
    <?PHP endforeach; ?>
</div>
<button id="btn-vote"> VOTE </button>
<script type="text/javascript" src="/assets/js/src/promo/estudyantrepreneur.js"></script>
