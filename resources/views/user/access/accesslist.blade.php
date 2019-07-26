<table class="table mt-2 table-striped table-hover" id="datatable">
        <thead class="thead-light">
        <tr>
            <th scope="col" class="align-middle">No</th>
            <th scope="col" class="align-middle">Name</th>
            <th scope="col" class="align-middle">URL</th>
            <th scope="col" class="align-middle text-center">Control</th>
        </tr>
    </thead>
    @php
    $i = ($access->currentPage()-1) * $access->perPage()+1 
    @endphp
    @if (isset($msg) && $msg != "")
        <tr><td colspan=4 class="text-center">{{$msg}}</td></tr>
    @endif
        @foreach ($access as $acc)
        <tr>
            <td class="align-middle"scope="row">{{$i++}}</td>
            <td class="align-middle">{{$acc->name}}</td>
            <td class="align-middle">{{$acc->url}}</td>
            <td class="align-middle text-center">
                <a href="{{"/". $username . "/daftarakses/" . $acc->id}}" data-id="{{ $acc->id }}" id="show-access" class="btn btn-success">Lihat</a>
                <a href="{{"/". $username . "/daftarakses/" . $acc->id . "/edit"}}" data-id="{{ $acc->id }}" id="edit-access" class="btn btn-info">Edit</a>
                <a href="{{"/". $username . "/daftarakses/" . $acc->id}}" data-id="{{ $acc->id }}" id="delete-access" class="btn btn-danger">Hapus</a>
            </td>
        </tr>
        @endforeach
    </table>
<div class="d-flex justify-content-center">{{ $access->links() }}</div>
