@extends('backend.layouts.master')

@section('title','Product Detail')

@section('main-content')
<div class="card">

    <div class="card-body">
        @if($product)

        <table class="table table-striped table-hover">
            @php
            $sub_cat_info=DB::table('categories')->select('title')->where('id',$product->child_cat_id)->get();
            @endphp
            <thead>
                <tr>
                    <th>S.N.</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Is Featured</th>
                    <th>Price</th>
                    <th>Discount</th>
                    <th>Size</th>
                    <th>Condition</th>
                    <th>Stock</th>
                    <th>Weight</th>
                    <th>Photo</th>
                    <th>Rating</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$product->id}}</td>
                    <td>{{$product->title}}</td>
                    <td>{{$product->cat_info['title']}}
                        <sub>
                            @foreach($sub_cat_info as $data)
                            {{$data->title}}
                            @endforeach
                        </sub>
                    </td>
                    <td>{{(($product->is_featured==1)? 'Yes': 'No')}}</td>
                    <td>Rp. {{$product->price}}</td>
                    <td> {{$product->discount}}% OFF</td>
                    <td>{{$product->size}}</td>
                    <td>{{$product->condition}}</td>

                    <td>
                        @if($product->stock>0)
                        <span class="badge badge-primary">{{$product->stock}}</span>
                        @else
                        <span class="badge badge-danger">{{$product->stock}}</span>
                        @endif
                    </td>
                    <td>
                        {{$product->weight}}
                    </td>
                    <td>

                        @php
                        $photo=explode(',',$product->photo);
                        // dd($photo);
                        @endphp

                        @foreach ($photo as $photos)
                        <img src="{{asset($photos)}}" class="img-fluid zoom" style="max-width:80px"
                            alt="{{$product->photo}}">

                        @endforeach

                    </td>
                    <td>
                        {{ceil($product->getReview->avg('rate'))}}
                        <span>(Overall)</span>
                    </td>
                    <td>
                        @if($product->status=='active')
                        <span class="badge badge-success">{{$product->status}}</span>
                        @else
                        <span class="badge badge-warning">{{$product->status}}</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>


        @endif

    </div>
</div>
@endsection

@push('styles')
<style>
    .order-info,
    .shipping-info {
        background: #ECECEC;
        padding: 20px;
    }

    .order-info h4,
    .shipping-info h4 {
        text-decoration: underline;
    }
</style>
@endpush