'use strict'
const openModalClassList = document.querySelectorAll('.modal-open')
const closeModalClassList = document.querySelectorAll('.modal-close')
const overlay = document.querySelector('.modal-overlay')
const body = document.querySelector('body')
const modal = document.querySelector('.modal')
const modalInnerHTML = document.getElementById('modalInner')

for (let i = 0; i < openModalClassList.length; i++) {
  openModalClassList[i].addEventListener('click', (e) => {
    e.preventDefault()
    let eventId = parseInt(e.currentTarget.id.replace('event-', ''))
    openModal(eventId)
  }, false)
}

for (var i = 0; i < closeModalClassList.length; i++) {
  closeModalClassList[i].addEventListener('click', closeModal)
}

overlay.addEventListener('click', closeModal)


async function openModal(eventId) {
  try {
    const url = '/api/getModalInfo.php?eventId=' + eventId
    const res = await fetch(url)
    const event = await res.json()
    let modalHTML = `
      <h2 class="text-md font-bold mb-3">${event.name}</h2>
      <p class="text-sm">${event.date}（${event.day_of_week}）</p>
      <p class="text-sm">${event.start_at} ~ ${event.end_at}</p>

      <hr class="my-4">

      <p class="text-md">
        ${event.message}
      </p>

      <hr class="my-4">
    
      <div class="accordion">
        <a class="accordion_click">
          <p class="text-sm"><span class="text-xl">${event.total_participants}</span>人参加 ></p>
        </a>
        <ul style="display: none">
          <p class="font-bold">参加者一覧：</p>
      `

      for (let i = 0; i < event.participant_names.length; i++) {
      modalHTML += `
          <li>${event.participant_names[i][0]}</li>
      `
      }

      modalHTML += `
        </ul>
      </div>
      `

    switch (event.participation_status) {
      case null:
        modalHTML += `
          <div class="text-center mt-6">
            <p class="text-lg font-bold text-yellow-400">未回答</p>
            <p class="text-xs text-yellow-400">期限 ${event.deadline}</p>
          </div>
          <div class="flex mt-5">
            <button class="flex-1 bg-gray-300 py-2 mx-3 rounded-3xl text-white text-lg font-bold" name="presence" value="presence" onclick="participateEvent(${eventId})">参加する</button>
            <button class="flex-1 bg-gray-300 py-2 mx-3 rounded-3xl text-white text-lg font-bold" name="absence" value="absence" onclick="nonParticipateEvent(${eventId})">参加しない</button>
          </div>
        `
        break;
      case 'presence':
        modalHTML += `
          <div class="text-center mt-10">
            <p class="text-xl font-bold text-green-400">参加</p>
          </div>
          <div class="flex mt-5">
            <button class="flex-1 bg-blue-500 py-2 mx-3 rounded-3xl text-white text-lg font-bold" disabled">参加する</button>
            <button class="flex-1 bg-gray-300 py-2 mx-3 rounded-3xl text-white text-lg font-bold" name="absence" value="absence" onclick="nonParticipateEvent(${eventId})">参加しない</button>
          </div>
        `
        break;
      case 'absence':
        modalHTML += `
          <div class="text-center mt-10">
            <p class="text-xl font-bold text-gray-300">不参加</p>
          </div>
          <div class="flex mt-5">
            <button class="flex-1 bg-gray-300 py-2 mx-3 rounded-3xl text-white text-lg font-bold" name="presence" value="presence" onclick="participateEvent(${eventId})">参加する</button>
            <button class="flex-1 bg-blue-500 py-2 mx-3 rounded-3xl text-white text-lg font-bold" disabled>参加しない</button>
          </div>
        `
        break;
    }


    // アコーディオン
    $(function(){
        $('.accordion_click').click(function(event){
            //クリックされた要素に隣接する要素が開いたり閉じたりする
            $(this).next('ul').slideToggle();
            // モーダルが開くのを防止
            return false;
        });
    });

    modalInnerHTML.insertAdjacentHTML('afterbegin', modalHTML)




  } catch (error) {
    console.log(error);
  }
  toggleModal()
}

function closeModal() {
  modalInnerHTML.innerHTML = ''
  toggleModal()
}

function toggleModal() {
  modal.classList.toggle('opacity-0')
  modal.classList.toggle('pointer-events-none')
  body.classList.toggle('modal-active')
}

async function participateEvent(eventId) {
  try {
    let formData = new FormData();
    let userId = document.querySelector("input[name='user_id']").value;
    let status = document.querySelector("button[name='presence']").value;
    formData.append('eventId', eventId)
    formData.append('userId', userId)
    formData.append('status', status)
    const url = '/api/postEventAttendance.php'
    await fetch(url, {
      method: 'POST',
      body: formData
    }).then((res) => {
      if (res.status !== 200) {
        throw new Error("system error");
      }
      return res.text();
    })
    closeModal()
    location.reload()
  } catch (error) {
    console.log(error)
  }
}

async function nonParticipateEvent(eventId) {
  try {
    let formData = new FormData();
    let userId = document.querySelector("input[name='user_id']").value;
    let status = document.querySelector("button[name='absence']").value;
    formData.append('eventId', eventId)
    formData.append('userId', userId)
    formData.append('status', status)
    const url = '/api/postEventAttendance.php'
    await fetch(url, {
      method: 'POST',
      body: formData
    }).then((res) => {
      if (res.status !== 200) {
        throw new Error("system error");
      }
      return res.text();
    })
    closeModal()
    location.reload()
  } catch (error) {
    console.log(error)
  }
}