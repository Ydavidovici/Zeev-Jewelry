<!DOCTYPE html>
<html>
<head>
    <title>GraphiQL</title>
    <link href="//unpkg.com/graphiql/graphiql.min.css" rel="stylesheet" />
</head>
<body style="margin: 0; height: 100vh;">
<div id="graphiql" style="height: 100vh;"></div>
<script crossorigin src="//unpkg.com/react/umd/react.production.min.js"></script>
<script crossorigin src="//unpkg.com/react-dom/umd/react-dom.production.min.js"></script>
<script crossorigin src="//unpkg.com/graphiql/graphiql.min.js"></script>
<script>
    const graphQLFetcher = graphQLParams =>
        fetch('/api/graphql', {  // Ensure the correct path to the GraphQL endpoint
            method: 'post',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(graphQLParams),
        }).then(response => response.json());

    ReactDOM.render(
        React.createElement(GraphiQL, { fetcher: graphQLFetcher }),
        document.getElementById('graphiql'),
    );
</script>
</body>
</html>
