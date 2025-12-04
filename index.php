<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PocketBase Example</title>    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>
<body>
    

    <script src="./node_modules/pocketbase//dist//pocketbase.umd.js"></script>
    <script type="text/javascript">
        const pb = new PocketBase("http://127.0.0.1:8090")
    // list and search for 'example' collection records
        async function fetchCategories() {
            const list = await pb.collection('post_categories').getList(1, 10);
            console.log(list);
        }
        fetchCategories();

    </script>
</body>
</html>
