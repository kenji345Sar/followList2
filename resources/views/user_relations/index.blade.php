<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Follow</th>
            <th>Follower</th>
            <th>Mutual</th>
            <th>Blocking</th>
            <th>Blocked By</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($relations as $id => $relation)
            <tr>
                <td>{{ $id }}</td>
                <td>{{ $relation['follow'] ? '○' : '' }}</td>
                <td>{{ $relation['follower'] ? '○' : '' }}</td>
                <td>{{ $relation['mutual'] ? '○' : '' }}</td>
                <td>{{ $relation['blocking'] ? '○' : '' }}</td>
                <td>{{ $relation['blocked_by'] ? '○' : '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
