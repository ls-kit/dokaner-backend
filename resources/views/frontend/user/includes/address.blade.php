<div class="row">
  @forelse ($addresses as $address)
  <div class="col-lg-6">
    <div class="card mb-3">
      <div class="card-header">
        <h4>Address #{{$loop->iteration}}</h4>
      </div>
      <div class="card-body defaultAddressCardBody p-2 px-3">
        <p class="m-0"><b>Name: </b><span class="name">{{$address->name}}</span></p>
        <p class="m-0"><b>Phone: </b><span class="phone_one">{{$address->phone_one}}<br></span></p>
        <p class="m-0"><b>Phone: </b><span class="phone_two">{{$address->phone_two}}<br></span></p>
        <p class="m-0"><b>Address: </b><span class="address">{{$address->name}}</span></p>
      </div>
    </div>
  </div>
  @empty
  <div class="col-lg-6">
    <div class="card mb-3">
      <div class="card-header">
        <h4>Address Not Found</h4>
      </div>
      <div class="card-body defaultAddressCardBody p-2 px-3">
        <p class="m-0"><b>Name: </b><span class="name">No Name</span></p>
        <p class="m-0"><b>Phone: </b><span class="phone_one">No Phone<br></span></p>
        <p class="m-0"><b>Phone: </b><span class="phone_two">No Phone<br></span></p>
        <p class="m-0"><b>Address: </b><span class="address">No Address</span></p>
      </div>
    </div>
  </div>
  @endforelse

</div>