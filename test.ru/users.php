<?php
require_once 'header.php';
?>

<div style="gap:10px"  class="user-list d-flex flex-wrap">
</div>
</div>
</body>
</html>

<script>
    const usersList = document.querySelector('.user-list')

    async function getUsers() {
        const res = await fetch('http://api.test.ru/users/');
        res.json().then(res => renderPost(res));
    }
    function renderPost(users) {
        let html = '';
        users.forEach((user) => {
            html += `
                <div class="card" style="width: 18rem;">
                  <div class="card-body">
                    <h5 class="card-title">${user.name} - ${user.username}</h5>
                    <p class="card-text">${user.email} -- ${user.website}</p>
                  </div>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">${user.company.name}</li>
                    <li class="list-group-item">${user.company.catchPhrase}</li>
                    <li class="list-group-item">${user.company.bs}</li>
                  </ul>
                  <div class="card-body">
                    <a href="/users/${user.id}" class="card-link">User link</a>
                  </div>
                </div>
            `
        })
        usersList.innerHTML = html;
    }
    getUsers();
</script>