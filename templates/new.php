<title>New Sales Questionnaire | Sontia</title>
<div class="row">
	<div class="columns">
		<h1>New sales questionnaire</h1>
		{% if response %}<div class="alert-box radius {{ response.status }}">{{ response.message }}</div>{% endif %}
	</div>
</div>
<form action="{{ url('salesquestionnaire.questionnaire.newForm') }}" method="post" id="new_questionnaire_form">
	<input type="hidden" name="requesttoken" value="{{ requesttoken }}" />
	{% include('_form.php') %}
	<div class="row">
		<div class="columns small-6"><input type="submit" value="Create" class="button expand" /></div>
		<div class="columns small-6"><a href="{{ url('salesquestionnaire.questionnaire.index') }}" class="button secondary expand">Cancel</a></div>
	</div>
</form>