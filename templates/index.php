<div class="row">
	<div class="columns">
		<h1>Sales questionnaire</h1>
		{% if response %}<div class="alert-box radius {{ response.status }}">{{ response.message }}</div>{% endif %}
	</div>
</div>
<div class="row">
	<div class="columns large-3">
		<a href="{{ url('salesquestionnaire.questionnaire.newForm') }}" class="button">Create new</a>
	</div>
	<div class="columns large-9">
		<form method="get" action="{{ url('salesquestionnaire.questionnaire.index') }}">
			<label class="hide">Search</label>
			<div class="row collapse">
				<div class="small-10 columns">
					<input type="search" name="search" value="{{ search ? search : "" }}" placeholder="Search for customer, project, platform, territories, OEM or creator"/>
				</div>
				<div class="small-2 columns">
					<input type="submit" value="Search" class="button secondary postfix">
				</div>
			</div>
		</form>
	</div>
</div>
{% if search %}
	<div class="row">
		<div class="columns">
			<p>Search results for: "<strong>{{ search }}</strong>". <a href="{{ url('salesquestionnaire.questionnaire.index') }}">clear search</a></p>
		</div>
	</div>
{% endif %}
<div class="row">
	<div class="columns large-12">
		{% if questionnaires %}
			<table>
				<thead>
					<tr>
						<th><a href="?sortby=customer{% if sortby == 'customer' %}&direction=desc" class="sort{% if direction == 'desc' %} desc{% endif %}{% endif %}">Customer</a></th>
						<th><a href="?sortby=projectName{% if sortby == 'projectName' %}&direction=desc" class="sort{% if direction == 'desc' %} desc{% endif %}{% endif %}">Project</a></th>
						<th class="hide-for-small"><a href="?sortby=platform{% if sortby == 'platform' %}&direction=desc" class="sort{% if direction == 'desc' %} desc{% endif %}{% endif %}">Platform</a></th>
						<th class="hide-for-small"><a href="?sortby=territories{% if sortby == 'territories' %}&direction=desc" class="sort{% if direction == 'desc' %} desc{% endif %}{% endif %}">Territories</a></th>
						<th class="hide-for-small"><a href="?sortby=oem{% if sortby == 'oem' %}&direction=desc" class="sort{% if direction == 'desc' %} desc{% endif %}{% endif %}">ODM / OEM</a></th>
						<th><a href="?sortby=createdAt{% if sortby == 'createdAt' %}&direction=desc" class="sort{% if direction == 'desc' %} desc{% endif %}{% endif %}">Created</a></th>
						<th class="hide-for-small"><a href="?sortby=uid{% if sortby == 'uid' %}&direction=desc" class="sort{% if direction == 'desc' %} desc{% endif %}{% endif %}">Created By</a></th>
						<th><a href="?sortby=updatedAt{% if sortby == 'updatedAt' %}&direction=desc" class="sort{% if direction == 'desc' %} desc{% endif %}{% endif %}">Updated</a></th>
						<th class="hide-for-small"><a href="?sortby=modifiedBy{% if sortby == 'modifiedBy' %}&direction=desc" class="sort{% if direction == 'desc' %} desc{% endif %}{% endif %}">Updated By</a></th>
					</tr>
				</thead>
				<tbody>
					{% for questionnaire in questionnaires %}
						<tr>
							<td><a href="{{ url('salesquestionnaire.questionnaire.show', {'Id':questionnaire.id}) }}">{{ questionnaire.customer }}</a></td>
							<td><a href="{{ url('salesquestionnaire.questionnaire.show', {'Id':questionnaire.id}) }}">{{ questionnaire.projectName }}</a></td>
							<td class="hide-for-small"><a href="{{ url('salesquestionnaire.questionnaire.show', {'Id':questionnaire.id}) }}">{{ questionnaire.platform }}</a></td>
							<td class="hide-for-small"><a href="{{ url('salesquestionnaire.questionnaire.show', {'Id':questionnaire.id}) }}">{{ questionnaire.territories }}</a></td>
							<td class="hide-for-small"><a href="{{ url('salesquestionnaire.questionnaire.show', {'Id':questionnaire.id}) }}">{{ questionnaire.oem }}</a></td>
							<td><a href="{{ url('salesquestionnaire.questionnaire.show', {'Id':questionnaire.id}) }}">{{ questionnaire.createdAt is empty ? "" : questionnaire.createdAt|date('d/m/y') }}</a></td>
							<td class="hide-for-small"><a href="{{ url('salesquestionnaire.questionnaire.show', {'Id':questionnaire.id}) }}">{{ questionnaire.uid }}</a></td>
							<td><a href="{{ url('salesquestionnaire.questionnaire.show', {'Id':questionnaire.id}) }}">{{ questionnaire.updatedAt is empty? "" : questionnaire.updatedAt|date('d/m/y') }}</a></td>
							<td class="hide-for-small"><a href="{{ url('salesquestionnaire.questionnaire.show', {'Id':questionnaire.id}) }}">{{ questionnaire.modifiedBy }}</a></td>
						</tr>
					{% endfor %}
				</tbody>
			</table>
		{% endif %}
	</div>
</div>