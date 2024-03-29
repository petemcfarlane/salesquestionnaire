<title>View Sales Questionnaire | Sontia Cloud</title>
<div class="row">
	<div class="columns">
		<h1>Sales questionnaire</h1>
		{% if response %}<div class="alert-box radius {{ response.status }}">{{ response.message }}</div>{% endif %}
	</div>
</div>
<div class="row">
	<div class="columns large-12">
		<div class="panel">
			<div class="row">
				<div class="columns large-3">
					<label class="inline">Created at:</label><p>{{ questionnaire.createdAt is empty? "" : questionnaire.createdAt|date("g:ia, j/m/y") }}</p>
				</div>
				<div class="columns large-3">
					<label class="inline">Created by:</label><p>{{ questionnaire.uid }}</p>
				</div>
				<div class="columns large-3">
					<label class="inline">Last modified at:</label><p>{{ questionnaire.updatedAt is empty ? "" : questionnaire.updatedAt|date("g:ia, j/m/y") }}</p>
				</div>
				<div class="columns large-3">
					<label class="inline">Last modified by:</label><p>{{ questionnaire.modifiedBy }}</p>
				</div>
			</div>
			<div class="relative">
				<span>
					{% if "UPDATE" in questionnaire['permissions'] or questionnaire['permissions'] is not defined %}
						<a class="button no-margin" href="{{ url('salesquestionnaire.questionnaire.edit', {'Id':questionnaire.id}) }}">Edit</a>
					{% endif %}
					{% if "SHARE" in questionnaire['permissions'] or questionnaire['permissions'] is not defined %}
						<a class="button secondary share no-margin" data-item-type="salesquestionnaire" data-item="{{ questionnaire.id }}" data-possible-permissions="31" data-private-link="false" data-link"true">Share</a>
					{% endif %}
					{% if "DELETE" in questionnaire['permissions'] or questionnaire['permissions'] is not defined %}
						<a class="button alert no-margin" href="{{ url('salesquestionnaire.questionnaire.delete', {'Id':questionnaire.id}) }}">Delete</a>
					{% endif %}
				</span>
			</div>
		</div>
	</div>
</div><div class="row">
	<div class="columns large-6{{ questionnaire.customer is empty ? ' empty' : '' }}">
		<label class="inline">Customer:</label><p>{{ questionnaire.customer }}</p>
	</div>
	<div class="columns large-3">
		<label class="inline{{ questionnaire.customerAddress ? '' : ' empty' }}">Customer address:</label><p>{{ questionnaire.customerAddress|nl2br }}</p>
	</div>
	<div class="columns large-3">
		<label class="inline{{ questionnaire.customerWebsite ? '' : ' empty' }}">Customer website:</label><p><a href="{{ questionnaire.customerWebsite }}" target="_blank">{{ questionnaire.customerWebsite }}</a></p>
	</div>
</div>
<div class="row">
	<div class="columns large-6">
		<label class="inline{{ questionnaire.projectName ? '' : ' empty' }}">Project name:</label><p>{{ questionnaire.projectName }}</p>
	</div>
	<div class="columns large-3">
		<label class="inline{{ questionnaire.projectType ? '' : ' empty' }}">Project type:</label><p>{{ questionnaire.projectType }}</p>
	</div>
	<div class="columns large-3">
		<label class="inline{{ questionnaire.platform ? '' : ' empty' }}">Platform:</label><p>{{ questionnaire.platform }}</p>
	</div>
</div>

{% if questionnaire.meetingWith %}
<div class="row">
	<div class="columns large-12">
		<label class="inline">Meeting with:</label>
			{% for contact in questionnaire.meetingWith %}
				<a class="contact_info_link" data-skip-pjax='true' href="/index.php/apps/contacts#{{ contact.id }}" data-id="{{ contact.id }}">{{ contact.fullname }}{{ loop.last ? "" : ", " }}</a>
			{% endfor %}
	</div>
</div>
{% endif %}
<div class="row">
	<div class="columns large-3">
		<label class="inline{{ questionnaire.meetingDate ? '' : ' empty' }}">Meeting date:</label><p>{{ questionnaire.meetingDate is empty ? "" : questionnaire.meetingDate|date("j/m/y") }}</p>
	</div>
	<div class="columns large-3">
		<label class="inline{{ questionnaire.meetingLocation ? '' : ' empty' }}">Meeting location:</label><p>{{ questionnaire.meetingLocation }}</p>
	</div>
	<div class="columns large-3">
		<label class="inline{{ questionnaire.representative ? '' : ' empty' }}">Representative:</label><p>{{ questionnaire.representative }}</p>
	</div>
</div>
<div class="row">
	<div class="columns large-12">
		<label class="inline{{ questionnaire.meetingPurpose ? '' : ' empty' }}">Meeting purpose:</label><p>{{ questionnaire.meetingPurpose }}</p>
	</div>
</div>
<div class="row">
	<div class="columns large-6">
		<label class="inline{{ questionnaire.technicalAuthority ? '' : ' empty' }}">Technical authority:</label><p><a class="contact_info_link" data-skip-pjax='true' href="/index.php/apps/contacts#{{ questionnaire.technicalAuthority.id }}" data-id="{{ questionnaire.technicalAuthority.id }}">{{ questionnaire.technicalAuthority.fullname }}</a></p>
	</div>
	<div class="columns large-6">
		<label class="inline{{ questionnaire.commercialAuthority ? '' : ' empty' }}">Commercial authority:</label><p><a class="contact_info_link" data-skip-pjax='true' href="/index.php/apps/contacts#{{ questionnaire.commercialAuthority.id }}" data-id="{{ questionnaire.commercialAuthority.id }}">{{ questionnaire.commercialAuthority.fullname }}</a></p>
	</div>
</div>
<div class="row">
	<div class="columns large-12">
		<label class="inline{{ questionnaire.technicalRequirements ? '' : ' empty' }}">Technical requirements:</label><p>{{ questionnaire.technicalRequirements|nl2br }}</p>
	</div>
</div>
<div class="row">
	<div class="columns large-12">
		<label class="inline{{ questionnaire.commercialRequirements ? '' : ' empty' }}">Commercial requirements:</label><p>{{ questionnaire.commercialRequirements|nl2br }}</p>
	</div>
</div>
<div class="row">
	<div class="columns large-12">
		<label class="inline{{ questionnaire.otherRequirements ? '' : ' empty' }}">Other requirements:</label><p>{{ questionnaire.otherRequirements|nl2br }}</p>
	</div>
</div>
<div class="row">
	<div class="columns large-12">
		<label class="inline{{ questionnaire.meetingNotes ? '' : ' empty' }}">Meeting notes:</label><p>{{ questionnaire.meetingNotes|nl2br }}</p>
	</div>
</div>
<div class="row">
	<div class="columns large-3">
		<label class="inline{{ questionnaire.purchaseDecision ? '' : ' empty' }}">Purchase decision:</label><p>{{ questionnaire.purchaseDecision is empty? "" : questionnaire.purchaseDecision|date("j/m/y") }}</p>
	</div>
	<div class="columns large-3">
		<label class="inline{{ questionnaire.supplyEvaluation ? '' : ' empty' }}">Supply evaluation:</label><p>{{ questionnaire.supplyEvaluation is empty ? "" : questionnaire.supplyEvaluation|date("j/m/y") }}</p>
	</div>
	<div class="columns large-3">
		<label class="inline{{ questionnaire.optimizeBy ? '' : ' empty' }}">Optimize by:</label><p>{{ questionnaire.optimizeBy is empty ? "" : questionnaire.optimizeBy|date("j/m/y") }}</p>
	</div>
	<div class="columns large-3">
		<label class="inline{{ questionnaire.manufactureDate ? '' : ' empty' }}">Manufacture date:</label><p>{{ questionnaire.manufactureDate is empty ? "" : questionnaire.manufactureDate|date("j/m/y") }}</p>
	</div>
</div>
<div class="row">
	<div class="columns large-3">
		<label class="inline{{ questionnaire.retailDate ? '' : ' empty' }}">Retail date:</label><p>{{ questionnaire.retailDate is empty ? "" : questionnaire.retailDate|date("j/m/y") }}</p>
	</div>
</div>
<div class="row">
	<div class="columns large-3">
		<label class="inline{{ questionnaire.minimumOrder ? '' : ' empty' }}">Minimum order:</label><p>{{ questionnaire.minimumOrder }}</p>
	</div>
	<div class="columns large-3">
		<label class="inline{{ questionnaire.rampYear1 ? '' : ' empty' }}">Ramp year 1:</label><p>{{ questionnaire.rampYear1 }}</p>
	</div>
	<div class="columns large-3">
		<label class="inline{{ questionnaire.rampYear2 ? '' : ' empty' }}">Ramp year 2:</label><p>{{ questionnaire.rampYear2 }}</p>
	</div>
	<div class="columns large-3">
		<label class="inline{{ questionnaire.rampYear3 ? '' : ' empty' }}">Ramp year 3:</label><p>{{ questionnaire.rampYear3 }}</p>
	</div>
</div>
<div class="row">
	<div class="columns large-6">
		<label class="inline{{ questionnaire.territories ? '' : ' empty' }}">Territories:</label><p>{{ questionnaire.territories }}</p>
	</div>
	<div class="columns large-6">
		<label class="inline{{ questionnaire.retailers ? '' : ' empty' }}">Retailers:</label><p>{{ questionnaire.retailers }}</p>
	</div>
</div>
<div class="row">
	<div class="columns large-3">
		<label class="inline{{ questionnaire.bom ? '' : ' empty' }}">BOM:</label><p>{{ questionnaire.bom }}</p>
	</div>
	<div class="columns large-3">
		<label class="inline{{ questionnaire.rrp ? '' : ' empty' }}">RRP:</label><p>{{ questionnaire.rrp }}</p>
	</div>
	<div class="columns large-3">
		<label class="inline{{ questionnaire.licenseFee ? '' : ' empty' }}">License fee:</label><p>{{ questionnaire.licenseFee }}</p>
	</div>
	<div class="columns large-3">
		<label class="inline{{ questionnaire.budgeted is null ? ' empty' : '' }}">Budgeted:</label><p>{% if questionnaire.budgeted == 1 %}yes{% elseif questionnaire.budgeted is sameas(0) %}no{% endif %}</p>
	</div>
</div>
<div class="row">
	<div class="columns large-3">
		<label class="inline{{ questionnaire.oem ? '' : ' empty' }}">OEM:</label><p>{{ questionnaire.oem }}</p>
	</div>
</div>
<div class="row">
	<div class="columns large-12">
		<label class="inline{{ questionnaire.convince ? '' : ' empty' }}">Convince:</label><p>{{ questionnaire.convince|nl2br }}</p>
	</div>
</div>
<div class="row">
	<div class="columns large-12">
		<label class="inline{{ questionnaire.tasks ? '' : ' empty' }}">Tasks:</label><p>{{ questionnaire.tasks|nl2br }}</p>
	</div>
</div>
<div class="row">
	<div class="columns large-12">
		<label class="inline{{ questionnaire.generalNotes ? '' : ' empty' }}">General notes:</label><p>{{ questionnaire.generalNotes|nl2br }}</p>
	</div>
</div>
<div class="row">
	<div class="columns large-12">
		<label class="inline{{ questionnaire.riskAssessment ? '' : ' empty' }}">Risk assessment:</label><p>{{ questionnaire.riskAssessment|nl2br }}</p>
	</div>
</div>