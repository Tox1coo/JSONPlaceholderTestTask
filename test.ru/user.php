<?php
require_once 'header.php';
?>

<div class="user">

</div>

<div  style="gap:10px"  class="posts-list d-flex flex-wrap">

</div>

<div  style="gap:10px"  class="todos-list d-flex flex-wrap">

</div>
</div>
<script>
    const userBody = document.querySelector('.user')
    const postsList = document.querySelector('.posts-list')
    const todosList = document.querySelector('.todos-list')
    let url = new URL(window.location.href);
    const id = url.pathname.split('/')[2];
    const data = {};
    async function getUser() {
        if(!isNaN(id)) {
            const res = await fetch(`http://api.test.ru/users/${id}/`);
            res.json().then(res => {
                if(res['error']) {
                    alert(res['error']);
                    window.location.href = "http://test.ru/"

                }else {
                    renderUser(res)
                }
            });
        } else {
            alert('Id empty')
        }
    }
    function renderUser(user) {

        let html = `
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
                     <a href="#" class="btn btn-primary todos">user todos</a>
                    <a href="#" class="btn btn-primary posts">user posts</a>

                  </div>
                </div>
        `
        userBody.innerHTML = html;

        document.querySelector('.todos').addEventListener('click', () => {
            getTodos(user.id)
        });
        document.querySelector('.posts').addEventListener('click', () => {
            getPosts(user.id)
        });

    }

    async function getTodos(userId) {
        postsList.style.display = 'none';
        todosList.innerHTML = '';
        todosList.style.display = 'block';
        postsList.innerHTML = '';
        let html = '';
        const res = await fetch(`http://api.test.ru/users/${userId}/todos`);
        res.json().then(res => {
            console.log(res)
            if(res['error']) {
                alert(res['error']);
                window.location.href = "http://test.ru/"

            }else {
                res.forEach((todo) => {
                    todosList.innerHTML += `
                     <div class="card" style="width: 18rem;">
                      <div class="card-body">
                        <h5 class="card-title">${todo.title}</h5>
                        <p class="card-text">${todo.completed ? 'Completed' : 'No completed'}</p>
                        <a href="http://test.ru/todos/${todo.id}" class="card-link">Card link</a>
                      </div>
                    </div>`
                })
            }
        });
    }
    async function getPosts(userId) {
        todosList.style.display = 'none';
        postsList.innerHTML = '';
        postsList.style.display = 'block';
        todosList.innerHTML = '';
        const res = await fetch(`http://api.test.ru/users/${userId}/posts`);
        res.json().then(res => {
            console.log(res)
            if(res['error']) {
                alert(res['error']);
                window.location.href = "http://test.ru/"

            }else {
                res.forEach((post) => {
                    todosList.innerHTML += `
                     <div class="card" style="width: 18rem;">
                      <div class="card-body">
                        <h5 class="card-title">${post.title}</h5>
                        <p class="card-text">${post.body}</p>
                        <a href="http://test.ru/posts/${post.id}" class="card-link">Card link</a>
                      </div>
                    </div>`
                })
            }
        });
    }

    getUser();
</script>
</body>

</html>