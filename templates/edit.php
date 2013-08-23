<title>Edit Sales Questionnaire | Sontia Cloud</title>
<div class="row">
	<div class="columns">
		<h1>Edit sales questionnaire</h1>
		{% if response %}<div class="alert-box radius {{ response.status }}">{{ response.message }}</div>{% endif %}
	</div>
</div>
<form action="{{ url('salesquestionnaire.questionnaire.edit', {'Id':questionnaire.id}) }}" method="post">
	<input type="hidden" name="requesttoken" value="{{ requesttoken }}" />
	{% include('_form.php') %}
	<div class="row">
		<div class="columns large-12">
			<input type="submit" value="Update" class="button" />
			<a href="{{ url('salesquestionnaire.questionnaire.index') }}" class="button secondary">Cancel</a>
		</div>
	</div>
</form>