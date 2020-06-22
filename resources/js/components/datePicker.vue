<template>
    <div class="datePicker">
        <div @click="isOpen = !isOpen">
            <span v-if="!!value && value.length">{{ value }}</span><span
                v-else
            >Pick a date</span>
        </div>
        <div
            v-if="isOpen"
            class="datePicker-window"
        >
            <div>
                <div @click="previous">
                    previous
                </div>
                <span>{{ months[date.current.month] }} {{ date.current.year }}</span>
                <div @click="next">
                    next
                </div>
            </div>
            <table
                id="calendar"
                class="table table-bordered table-responsive-sm"
            >
                <thead>
                    <tr>
                        <th>Sun</th>
                        <th>Mon</th>
                        <th>Tue</th>
                        <th>Wed</th>
                        <th>Thu</th>
                        <th>Fri</th>
                        <th>Sat</th>
                    </tr>
                </thead>

                <tbody id="calendar-body">
                    <tr
                        v-for="(row,indexR) in table.rows"
                        :key="'row-' + indexR"
                    >
                        <td
                            v-for="(column,indexC) in row"
                            :key="'row-' + indexR + '-column-' + indexC"
                        >
                            <div
                                v-if="column"
                                :class="{'selected' : parseInt(date.current.day) === parseInt(column)}"
                                @click="pushDate(column)"
                            >
                                {{ column }}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div>
                <div><b>Jump to</b></div>
                <label>
                    Year:
                    <select
                        v-model="date.current.year"
                        @change="yearMonthIntUpdate"
                    >
                        <option
                            v-for="n in 11"
                            v-if="date.today"
                            :key="'year-' + n"
                            :value="n + date.today.getFullYear() - 1"
                        >
                            {{ n + date.today.getFullYear() - 1 }}
                        </option>
                    </select>
                </label>
                <label>
                    Month:
                    <select
                        v-model="date.current.month"
                        @change="yearMonthIntUpdate"
                    >
                        <option
                            v-for="(month,index) in months"
                            :key="'month-' + index"
                            :value="index"
                        >
                            {{ month }}
                        </option>
                    </select>
                </label>
            </div>
            <div>
                <h3>Time: <span style="font-weight: 300">{{ getTimeString(time) }}</span></h3>
                <input
                    v-model="time"
                    style="width: 100%"
                    type="range"
                    min="0"
                    :max="24 * 12"
                    @change="updateValue"
                >
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['value'],
    data () {
        return {
            isOpen: false,
            selectedDate: {
                start: '',
                end: ''
            },
            table: {
                rows: []
            },
            date: {
                today: null,
                current: {
                    day: null,
                    month: null,
                    year: null
                }
            },
            time: null,
            months: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        };
    },
    mounted () {
        this.date.today = new Date();
        if (this.value && this.value.length) {
            const chosenDate = new Date(this.value);
            this.date.current.day = chosenDate.getDate();
            this.date.current.month = chosenDate.getMonth();
            this.date.current.year = chosenDate.getFullYear();
            this.time = chosenDate.getHours() * 12 + chosenDate.getMinutes() - 12;
            this.updateValue();
        } else {
            this.date.current.day = this.date.today.getDate();
            this.date.current.month = this.date.today.getMonth();
            this.date.current.year = this.date.today.getFullYear();
            this.time = 12 * 12;
        }
        this.updateCalender();
    },
    methods: {
        updateValue () {
            const date = new Date(0);
            date.setFullYear(this.date.current.year, this.date.current.month, this.date.current.day);
            date.setMonth(this.date.current.month);
            date.setDate(this.date.current.day);
            date.setHours(this.getHours(this.time));
            date.setMinutes(this.getMinutes(this.time));
            this.value = this.$formatDate(true, date);
            console.log('----');
            console.log(this.value);
            console.log('----');
            this.$emit('update', this.value);
        },
        getTimeString (time) {
            const hours = this.getHours(time);
            const minutes = this.getMinutes(time);

            let hourString = hours + '';
            if (hourString.length === 1) {
                hourString = '0' + hourString;
            }
            let minuteString = minutes + '';

            if (minuteString.length === 1) {
                minuteString = '0' + minuteString;
            }
            return hourString + ':' + minuteString;
        },
        getHours (time) {
            return Math.floor(time / 12);
        },
        getMinutes (time) {
            return (time % 12) * 5;
        },
        next () {
            this.date.current.year = (this.date.current.month === 11) ? this.date.current.year + 1 : this.date.current.year;
            this.date.current.month = (this.date.current.month + 1) % 12;
            this.updateCalender();
        },
        previous () {
            this.date.current.year = (this.date.current.month === 0) ? this.date.current.year - 1 : this.date.current.year;
            this.date.current.month = (this.date.current.month === 0) ? 11 : this.date.current.month - 1;
            this.updateCalender();
        },
        yearMonthIntUpdate () {
            this.date.current.year = parseInt(this.date.current.year);
            this.date.current.month = parseInt(this.date.current.month);
            this.updateCalender();
        },
        updateCalender () {
            const firstDay = (new Date(this.date.current.year, this.date.current.month)).getDay();
            const daysInMonth = 32 - new Date(this.date.current.year, this.date.current.month, 32).getDate();

            // clearing all previous cells
            this.table.rows = [];

            // creating all cells
            let date = 1;
            for (let i = 0; i < 6; i++) {
                // creates a table row
                const row = [];

                // creating individual cells, filing them up with data.
                for (let j = 0; j < 7; j++) {
                    if (i === 0 && j < firstDay) {
                        const column = '';
                        row.push(column);
                    } else if (date > daysInMonth) {
                        break;
                    } else {
                        const column = '' + date;
                        row.push(column);
                        date++;
                    }
                }
                this.table.rows.push(row);
            }
            this.updateValue();
        },
        pushDate (day) {
            this.date.current.day = day;
            this.updateValue();
        }
    }
};
</script>
<!--TODO: Give some actual good styling and not in the .VUE file-->
<style lang="scss">
    .datePicker-window {
        padding: 24px;
        width: max-content;
        background: #eee;
    }

    .selected {
        background: #639;
        color: white;
    }
</style>
