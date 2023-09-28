import React from 'react';
//import { ArrwTopRightONSquareIcon, TrashIcon} from '@heroicons/react/24/outline';
//import TButton from './core/TButton';


export default function SurveyListItem({ surveys, onDeleteClick }){

	return (
		<>
		<div className="flex flex-col py-4 px-6 shadow-md bg-white hover:bg-gray-50 h-[470px">
		<img
			src={surveys.image_url}
			alt={surveys.title}
			className="w-full h-48 object-cover" 
			/>
			<h4 className="mt4 text-lg font-bold">{surveys.title}</h4>
			
			<div className="flex justify-between items-center mt-3">
				<button to={'surveys/${surveys.id}'}>
				
				Edit
				</button>
				{surveys.id &&(
				<button onClick={onDeleteClick} circle link color="red">
				Demo
				
				</button>
				)}
			</div>
		</div>
		</>
	)
}