<h4>Hi {{ $data->user_detail->adjusters->name}} </h4>

<p>Please approve this request</p>
<ul>
	<li>Document Type 	: {{ $data->detail_approval['document_type'] }}</li>
	<li>Document Title :  {{ $data->detail_approval['document_title'] }}</li>
	<li>Request By 		:  {{ $data->detail_approval['document_author'] }}</Li>
	<li>Detail <a href="{{ url('/')}}">Click Here</a></li>
</ul>


<p>Best Regards</p>
<h1>&nbsp;</h1>
<p>Atlas Team</p>