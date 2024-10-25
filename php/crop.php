<div class="hidden cropperDiv z-[2]">
    <div class="controls relative">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9c/Oscar_Wilde_portrait.jpg/250px-Oscar_Wilde_portrait.jpg" id="imgCrop" style="width: auto; height: 75vh;">
        <div class="horizontal group w-full flex justify-center absolute bottom-[-36px]">
            <span data-crop="vertical" class="svg-btn opacity-20 group-hover:opacity-100 bg-[#ffffff]/20 hover:bg-[#ffffff]/40 cursor-pointer rounded-[50%] mr-1 w-[30px] h-[30px] flex justify-center items-center transition-all duration-[0.7s]"><?php include './php/svg/vertical.svg' ?></span>
            <span data-crop="horizontal" class="svg-btn opacity-20 group-hover:opacity-100 bg-[#ffffff]/20 hover:bg-[#ffffff]/40 cursor-pointer rounded-[50%] mr-1 w-[30px] h-[30px] flex justify-center items-center transition-all duration-[0.7s]"><?php include './php/svg/horizontal.svg' ?></span>
            
            <span data-crop="reset" class="svg-btn opacity-20 group-hover:opacity-100 bg-[#ffffff]/20 hover:bg-[#ffffff]/40 cursor-pointer rounded-[50%] mr-1 w-[30px] h-[30px] flex justify-center items-center transition-all duration-[0.7s]"><?php include './php/svg/reset.svg' ?></span>
            
        </div>
        <div class="vertical group absolute top-0 right-[-36px]"> 
            <span data-crop="save" class="svg-btn opacity-20 group-hover:opacity-100 bg-[#ffffff]/20 hover:bg-[#ffffff]/40 cursor-pointer rounded-[50%] mr-1 w-[30px] h-[30px] flex justify-center items-center transition-all duration-[0.7s]"><?php include './php/svg/ok.svg' ?></span>
            <span data-crop="cancel" class="svg-btn opacity-20 group-hover:opacity-100 bg-[#ffffff]/20 hover:bg-[#ffffff]/40 cursor-pointer rounded-[50%] mr-1 w-[30px] h-[30px] flex justify-center items-center transition-all duration-[0.7s]"><?php include './php/svg/cancel.svg' ?></span>
    </div>
    </div>
</div>