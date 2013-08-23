<title>Delete Sales Questionnaire | Sontia Cloud</title>
<div class="row">
	<div class="columns">
		<h1>Delete sales questionnaire</h1>
		{% if response %}<div class="alert-box radius {{ response.status }}">{{ response.message }}</div>{% else %}
		<p>Are you sure you want to delete the sales questionnaire for <strong>{{ questionnaire.customer }}</strong>?</p>
			<form action="{{ url('salesquestionnaire.questionnaire.destroy', {'Id':questionnaire.id}) }}" method="post" class="inline">
				<input type="hidden" name="requesttoken" value="{{ requesttoken }}" />
				<input type="submit" value="Delete" class="button alert" />
			</form>
			<a href="{{ url('salesquestionnaire.questionnaire.show', {'Id':questionnaire.id}) }}" class="button secondary">Cancel</a>
			{% endif %}
	</div>
</div>
