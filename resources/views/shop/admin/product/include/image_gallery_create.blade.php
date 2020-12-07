<div class="box box-primary box-solid file-upload">
    <div class="box-header">
        <h3 class="box-title">Изображения галереи</h3>
    </div>
    <div class="box-body" id="gallery">
        <div id="multi" class="btn btn-primary" data-url="{{ url('/admin/products/gallery') }}"
             data-name="multi">Загрузить</div>
        <div class="multi">
            <p></p>{{--Отступ от кнопки--}}

        </div>
        <p>
            <small>Рекомендуемые размеры: 700ширина и 1000высота,
                иначе изображение автоматически сожмется</small><br>
            <small>Вы можете загружать по очереди любое кол-во</small>
        </p>
    </div>
    <!-- my.css .overlay -->
    <div class="overlay">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>
