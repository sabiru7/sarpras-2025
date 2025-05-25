<h1>Daftar Barang</h1>
<a href="{{ route('barangs.create') }}">Tambah Barang</a>
<table>
    <tr>
        <th>Nama Barang</th>
        <th>Kategori</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    @foreach ($barangs as $barang)
    <tr>
        <td>{{ $barang->nama_barang }}</td>
        <td>{{ $barang->kategori }}</td>
        <td>{{ $barang->status ? 'Tersedia' : 'Tidak Tersedia' }}</td>
        <td>
            <a href="{{ route('barangs.edit', $barang->id) }}">Edit</a>
            <form action="{{ route('barangs.destroy', $barang->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
