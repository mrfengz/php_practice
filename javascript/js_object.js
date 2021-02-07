/* 继承 */

// 克隆一个对象
function clone(object){
	function OneShotConstructor(){};
	OneShotConstructor.prototype = object;
	return new OneShotConstructor();
}

function LifeLikeTerrarium(plan) {
	Terrarium.apply(this, plan);
}

LifeLikeTerrarium.prototype = clone(Terrarium.prototype);
// 将构造函数改为自己
LifeLikeTerrarium.prototype.constructor = LifeLikeTerrarium;