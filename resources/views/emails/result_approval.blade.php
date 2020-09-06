<h4>Hi {{ $data->created_data->name}} </h4>

<p>Here the result of your request approval</p>
<ul>
	<li>Document Type 	:  {{ $data->detail_data['document_type']}}</li>
	<li>Status 			:   <span style="{{ $data->detail_data['class'] }}">{{ $data->detail_data['status'] }}</span></Li>
	<li>Description 	:  {{ $data->detail_data['description'] }}</li>
</ul>
<p>Please check this link to get detail<a>{{ url('/')}}</a></p>

<p>Best Regards</p>
<h1>&nbsp;</h1>
<p>Atlas Team</p>